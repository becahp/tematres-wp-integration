# Tematres WP Integration

## English

Plugin that implements the integration of a Tematres with WordPress.

When informing a Tematres API URL, WP recognizes the terms registered in Tematres as Tags available to publish to posts.

### Usage
Install and activate the plugin. Go to the "Tematres WP Integration" menu in the panel and configure the requirements:
- Tematres API URL
- Tag Name
- Post where the tags will be applied

### FrontEnd Observation

To return the tags in the frontend of a post which uses, for example, the ``get_the_tag_list`` function (as the Twenty Twenty One Theme) of WordPress, it is necessary to manually change the theme's template files, to not call this function, since it exclusively calls tags of type `post_tag`, which are standard in WP. So we created the functions `has_tag_thematres_wp` and `tmwpi_get_the_tag_list` that look for the tag created by the plugin.


Usage example in Twenty Twenty One theme:

```php
    if ( has_category() || has_tag() || tmwpi_has_tag() ) {
        ...
        if ( function_exists( 'tmwpi_get_the_tag_list' ) ) {
            $tags_list = tmwpi_get_the_tag_list( '', __( ' ', 'twentytwentyone' ) );
        } else {
            $tags_list = get_the_tag_list( '', __( ' ', 'twentytwentyone' ) );
        }
        ...
    }
```
## Português

Plugin que implementa a integração de um Tematres com o WordPress.

Ao informar a URL da API um Tematres, o WP reconhecerá os termos cadastrados no Tematres como Tags disponíveis para adicionar aos posts.

### Modo de usar

Instale e ative o plugin. Vá no menu "Tematres WP Integration" no painel e configure os requisitos:
- URL da API do Tematres
- Nome da Tag
- Post onde as tags serão aplicadas

## Observação para o Frontend:
Para retornar as tags no frontend de um post que usa, por exemplo, a função ``get_the_tag_list`` (como o Tema Twenty Twenty One) do wordpress, é necessário alterar manualmente os arquivos de template do tema, para não chamar essa função, uma vez que ela excluisvamente chama tags do tipo `post_tag`, que são padrão do WP. Assim, criamos `tmwpi_has_tag` e `tmwpi_get_the_tag_list` que buscam a tag criada pelo plugin.

Exemplo de uso no tema Twenty Twenty One:

```php
    if ( has_category() || has_tag() || tmwpi_has_tag() ) {
        ...
        if ( function_exists( 'tmwpi_get_the_tag_list' ) ) {
            $tags_list = tmwpi_get_the_tag_list( '', __( ' ', 'twentytwentyone' ) );
        } else {
            $tags_list = get_the_tag_list( '', __( ' ', 'twentytwentyone' ) );
        }
        ...
    }
```
