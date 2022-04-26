# tematres-wp

Plugin que implementa a integração de um Tematres com o WordPress
Ao informar a URL de um Tematres, o WP reconhecerá os termos cadastrados no Tematres como Tags disponíveis para adicionar aos posts.
Esse plugin toma controle das Tags nativas do WP (WP Core).

## TODO
- [x] Plugin base com um campo para entrada da URL do Tematres
- [ ] API do tematres
    - [ ] Pegar resultados de uma query de busca
    - [ ] Tratamento dos resultados (a partir do xml retornar um vetor de strings)
- [ ] Integrar ao WP
    - [ ] Esconder campo de tags nativos do WP (ou sobrepor???)
    - [ ] Criar campo customizado para seleção de tags (com choices.js), populado pelo vetor de strings já criado
    - [ ] Após seleção das tags, criá-las dentro do WP Core
    - [ ] Associar as tags ao post em questão
