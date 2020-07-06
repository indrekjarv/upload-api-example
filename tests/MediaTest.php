<?php

use PHPUnit\Framework\TestCase;

class MediaTest extends TestCase
{
    public function testPost_Upload_Fail()
    {
        $client = new GuzzleHttp\Client(
            [
                'base_uri' => 'http://upload-api.lan'
            ]
        );

        $filesize = filesize('tests/fail1.jpg');

        $fp = fopen('tests/fail1.jpg', 'rb');
        $binary = fread($fp, $filesize);
        fclose($fp);

        $response = $client->post(
            '/upload',
            [
                'json' => [
                    'account_id' => '2',
                    'media_name' => 'fail1.jpg',
                    'media_size' => $filesize,
                    'media_type' => 'image/jpeg',
                    'media_content' => base64_encode($binary)
                ]
            ]
        );

        $this->assertEquals(201, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $this->assertEquals(null, $data['data']);
    }

    public function testGet_Media()
    {
        $client = new GuzzleHttp\Client(
            [
                'base_uri' => 'http://upload-api.lan'
            ]
        );

        $response = $client->get('/media');
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('media_name', $data['data'][0]);
    }

    public function testDelete()
    {
        $client = new GuzzleHttp\Client(
            [
                'base_uri' => 'http://upload-api.lan'
            ]
        );

        $response = $client->get('/media');
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $fail_id = $data['data'][0]['id'];

        $response = $client->delete(
            '/media/' . $fail_id,
            [
                'http_errors' => false
            ]
        );

        $this->assertEquals(200, $response->getStatusCode());
    }

}



