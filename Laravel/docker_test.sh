#!/bin/bash
docker run -it -p 8000:80 -e DB_DATABASE=postgres -e DB_USERNAME=postgres -e DB_PASSWORD=pg\!fcp ezspecial/ldso
