<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class FirebaseBookTest extends TestCase
{
    /**
     * /firebase [GET].
     */
    public function testFirebaseGetAllBook()
    {
        $this->get('/api/firebase');
        $this->seeStatusCode(200);

        $this->seeJsonStructure(
            [
                "status",
                "message",
                "data"
            ]
        );
    }

    /**
     * /firebase/{slug} [GET].
     */
    public function testFirebaseGetBookBySlug()
    {
        $this->get('/api/firebase/sejarah-pulau-jawa', []);
        $this->seeStatusCode(200);

        $this->seeJsonStructure(
            [
                "status",
                "message",
                "data"
            ]
        );
    }

    /**
     * /firebase/{slug} [DELETE].
     */
    public function testFirebaseDeleteBookBySlug()
    {
        $this->delete('/api/firebase/indonesia2222', []);
        $this->seeStatusCode(404);

        $this->seeJsonStructure(
            [
                'status',
                'message',
                'data'
            ]
        );
    }
}
