# upload-api-example

git clobe https://github.com/indrekjarv/upload-api-example.git

cd upload-api-example

mkdir storage

composer install

cp .env.example .env


Run phpunits demo tests (NB! replace base_uri values):

phpunit tests


CURL examples (NB! replace URLs):

cd curl

curl_post_upload_json.txt - how to send data to API

make_json_test_fail.php - test fail generator

curl_get_media.txt - how to read all records

curl_get_media_2.txt - how to read one record

curl_delete_media.txt - hot to delete