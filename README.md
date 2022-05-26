# tematres-wp

Plugin que implementa a integração de um Tematres com o WordPress.

Ao informar a URL de um Tematres, o WP reconhecerá os termos cadastrados no Tematres como Tags disponíveis para adicionar aos posts.

Esse plugin toma controle das Tags nativas do WP (WP Core).

## TODO
- [x] Plugin base com um campo para entrada da URL do Tematres
- [ ] API do tematres
    - [x] Pegar resultados de uma query de busca
    - [X] Tratamento dos resultados (a partir do xml retornar um vetor de strings)
- [ ] Integrar ao WP
    - [X] Esconder campo de tags nativos do WP (ou sobrepor???)
    - [X] Criar campo customizado para seleção de tags (com ~~~choices.js~~ select2.js), populado pelo vetor de strings já criado
    - [X] Após seleção das tags, criá-las dentro da ~~WP Core~~ nova taxonomia criada
    - [ ] Associar as tags ao post em questão


O QUE FALTA FAZER
[ ] Traduzir tudo para inglês
[X] Esconder as tags "tematres_wp" do menu
[ ] Remover os comentários "rabicho de código"
[X] Esconder da interface do edit