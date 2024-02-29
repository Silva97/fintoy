# Fintoy

Só um projeto de "brinquedo" que simula uma plataforma financeira, permitindo
transferir valores monetários entre usuários.

**Design doc**: <https://docs.google.com/document/d/1Z-Z3HyQ707oPxdwwxmxtCa9NA7jfTRi3nwJ9WXeCQmQ/edit?usp=sharing>


## Ambiente de desenvolvimento

Para iniciar o servidor:

```console
$ php artisan serve
```

Para iniciar o *worker* da fila:

```console
$ php artisan queue:work
```
