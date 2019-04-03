# Test 

### What's been done

- A docker container is set up
- Used latest version of symfony using composer
- The code is under app/src
- Tests are under app/tests

### How to install

```$xslt
docker-compose up -d
``` 

### How to test

There are two types of tests. Integration and Unit.

To run the test, enter into the php-test container:

```$xslt
docker exec -it php-test bash

composer install
```


Integration test is for Command Handler, which is layer below the Request. This mimics the behaviour of request. 

Hence run the following to check if functionality is working:

```$xslt
vendor/bin/phpunit tests/Integration/CommandHander
```

To run other tests: 

```$xslt
vendor/bin/phpunit tests/Unit
```

###ToDos

1. Add Controller/UI or Command
2. Add database layer: At the moment data is handled in session, which wouldn't be the case in production. That's only for demo purpose.
3. Create Rule Engine: As the requirement of offers is simple at the moment, it would work with what it's here now. But, for future proof, I would crete separate offer entity
recognize by their types and Rules for each offer. 
For this project, it would have been too big requirement.

