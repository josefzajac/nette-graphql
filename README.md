# Nette + webonyx/GraphQL API + jQuery

### Prerequisities
- Local docker and node js

## Build

1) Run `docker-compose up`

2) Load data-fixtures `docker-compose exec php php bin/console doctrine:fixtures:load --no-interaction`

3) Sass compile
	- Install nvm https://github.com/nvm-sh/nvm#manual-install
	- Run `nvm install && npm install`
	- Build local assets npm `node_modules/sass/sass.js www/assets/css/main.scss www/dist/css/main.css`
	- I know, should be in php container or as separate node container

4) Open http://localhost:8081

5) Open http://localhost:8081/graphiql and check gql api


Execute Queries:
```
{brands(itemsPerPage: 3, page: 1) {
	items {id,name}
	currentPage,totalCount,perPage}}
```
```
mutation{createBrand(name:"xx"){id,name}}
```
```
mutation{updateBrand(name:"yy", id:101){id,name}}
```
```
mutation{deleteBrand(id:44)}
```

### Database ###
![ER diagram](brands-er.png)
