# tematres-wp

Plugin que implementa a integração de um Tematres com o WordPress.

Ao informar a URL de um Tematres, o WP reconhecerá os termos cadastrados no Tematres como Tags disponíveis para adicionar aos posts.

Esse plugin toma controle das Tags nativas do WP (WP Core).

## TODO
- [x] Plugin base com um campo para entrada da URL do Tematres
- [X] API do tematres
    - [x] Pegar resultados de uma query de busca
    - [X] Tratamento dos resultados (a partir do xml retornar um vetor de strings)
- [X] Integrar ao WP
    - [X] Esconder campo de tags nativos do WP (ou sobrepor???)
    - [X] Criar campo customizado para seleção de tags (com ~~~choices.js~~ select2.js), populado pelo vetor de strings já criado
    - [X] Após seleção das tags, criá-las dentro da ~~WP Core~~ nova taxonomia criada
    - [X] Associar as tags ao post em questão
    - [X] mostrar tags associadas


O QUE FALTA FAZER
[X] Traduzir tudo para inglês
[X] Esconder as tags "tematres_wp" do menu
[ ] Remover os comentários "rabicho de código"
[X] Esconder da interface do edit
[ ] Adicionar opção caso a tematres_tag_name não estiver setada
[X] forçar Wp a utilizar a língua inglesa do Plugin (ou pt_br)?

Adicionar observação:
Para retornar as tags no frontend de um post, que usa por exemplo a função ``get_the_tag_list`` (como o Tema Twenty Twenty One) do wordpress, é necessário alterar manualmente os arquivos de template do tema, para não chamar essa função, uma vez que ela excluisvamente chama tags do tipo `post_tag`, que são padrão do WP.

Exemplo de uso no tema Twenty Twenty One:

```php
    if ( function_exists( 'get_the_tag_list_tematres_wp' ) ) {
        $tags_list = get_the_tag_list_tematres_wp( '', __( ' ', 'twentytwentyone' ) );
    } else {
        $tags_list = get_the_tag_list( '', __( ' ', 'twentytwentyone' ) );
    }
```