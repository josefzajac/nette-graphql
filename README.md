
1) Run `docker-compose up`

2) Run `docker-compose exec php php bin/console doctrine:fixtures:load --no-interaction`

3) Open http://localhost:8081/graphiql and enjoy!

Execute Query:
```json
{brands(itemsPerPage:3, page:2){id, name}}
```
