# Test samples for the MVC-Core Rest API

## Create order (i.e. POST)
```
curl -X POST http://iut.uca.netspace.fr/iotia/mvc-core/api/rest/order -d @order.create.json -H 'Content-Type: application/json'
```
## Read order (i.e. GET)

### All orders :
```
curl -X GET http://iut.uca.netspace.fr/iotia/mvc-core/api/rest/order

curl -X GET http://iut.uca.netspace.fr/iotia/mvc-core/api/rest/order -o order.all.json
```
### order with id = 12 :
```
curl -X GET http://iut.uca.netspace.fr/iotia/mvc-core/api/rest/order/12

curl -X GET http://iut.uca.netspace.fr/iotia/mvc-core/api/rest/order/12 -o order.12.json
```
## Update order (i.e. PUT) : replace {id} by an existing id
```
curl -X PUT http://iut.uca.netspace.fr/iotia/mvc-core/api/rest/order/{id} -d @order.update.json -H 'Content-Type: application/json'
```
## Delete order (i.e. DELETE) : replace {id} by an existing id
```
curl -X DELETE http://iut.uca.netspace.fr/iotia/mvc-core/api/rest/order/{id}
```