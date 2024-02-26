<?php

test('example', function () {
    $response = $this->get('/api/');

    $response->assertStatus(200);
});
