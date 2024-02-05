## Requirements for building and running the application

- [Composer](https://getcomposer.org/download/)
- [Docker](https://docs.docker.com/get-docker/)
- [Make](https://www.makeuseof.com/how-to-fix-make-command-not-found-error-ubuntu/)

## Application Build and Run

After cloning the repository run:
`cp .env.example .env`

`make up`

`composer install`

`make migrate`

`make fake-data`

`make test`

`make coverage`


# Endpoints: 

### Docs Web Version http://localhost:8080/docs

### /api/clients/{clientId}/accounts
[Controller](src/Controller/ClientAccountsController.php)


#### method: GET

response example:
```json
{
  "accounts": [
    {
      "id": 1,
      "client": 1,
      "balance": 26022,
      "currency": "EUR"
    }
  ]
}
```
### /api/accounts/{accountId}/transactions?limit=1&offset=2

### #method: GET
##### optional query params:
_int_ limit

_int_ offset

response example:
```json
 {
  "transactions": [
    {
      "id": 3,
      "type": "outgoing",
      "sender": 2,
      "receiver": 4,
      "currency": "USD",
      "amount": 1000,
      "date": "2024-01-22T12:04:31+00:00"
    },
    {
      "id": 2,
      "type": "incoming",
      "sender": 1,
      "receiver": 2,
      "currency": "USD",
      "amount": 1089,
      "date": "2024-01-22T11:47:22+00:00"
    }
  ]
}
```

### /api/transfer
#### method: POST
##### required post params:
_int_ sourceAccountId

_int_ targetAccountId

_int_ amount

response example:
```json
{
  "success": true,
  "message": "Successfully Transferred"
}
```
