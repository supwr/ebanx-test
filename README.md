## Proposal

The API consists of two endpoints, GET /balance, and POST /event. Using your favorite programming language, build a system that can handle those requests.

## Follow the steps bellow to get the application up and running

### 1 - Build the app

```$ make build```

### 2 - Start the application

```$ make start-app```

### 3 - Setup and install the app dependencies

```$ make setup```

### 4 - To stop the application

```$ make stop-app```

## Requests

### Reset accounts | POST /reset

```
curl --location --request POST 'localhost:8080/reset' \
--header 'Content-Type: application/json' \
--data-raw '{
    "type": "deposit",
    "destination": 100,
    "amount": 10
}'
```

### Make a deposit | POST /event

```
curl --location --request POST 'localhost:8080/event' \
--header 'Content-Type: application/json' \
--data-raw '{
    "type": "deposit",
    "destination": "100",
    "amount": 1000
}'
```

### Withdraw from account | POST /event

```
curl --location --request POST 'localhost:8080/event' \
--header 'Content-Type: application/json' \
--data-raw '{
    "type": "withdraw",
    "origin": "100",
    "amount": 5
}'
```

### Transfer to another account | POST /event

```
curl --location --request POST 'localhost:8080/event' \
--header 'Content-Type: application/json' \
--data-raw '{
    "type": "transfer",
    "origin": "100",
    "amount": 15,
    "destination": "300"
}'
```

### Tests

To execute the unit tests

```$ make run-tests```

This project has ~65% code coverage

![Image](src/public/img/code-coverage.png?raw=true)
