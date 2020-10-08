<?php

namespace App\Tests;

use App\DataFixtures\UserFixtures;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EspecialidadesWebTest extends WebTestCase
{
    public function testGaranteQueRequisicaoFalhaSemAutenticacao()
    {
        $client = static::createClient();
        $client->request('GET', '/especialidades');

        self::assertEquals(401, $client->getResponse()->getStatusCode());
    }

    public function testGaranteQueEspecialidadesSaoListadas()
    {
        $client = static::createClient();
        $token = $this->login($client);
        $client->request('GET', '/especialidades', [], [], [
            'HTTP_AUTHORIZATION' => "Bearer $token"
        ]);

        $reposta = json_decode($client->getResponse()->getContent());

        self::assertTrue($reposta->sucesso);
        self::assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testInsereEspecialidade()
    {
        $client = static::createClient();
        $token = $this->login($client);
        $client->request('POST', '/especialidades', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => "Bearer $token"
        ], json_encode([
            'descricao' => 'Especialidade Teste'
        ]));

        self::assertEquals(201, $client->getResponse()->getStatusCode());
    }

    private function login($client): string
    {
        $this->loadUserFixture($client);
        $client->request('POST', '/login', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'usuario' => 'usuario',
            'senha' => '123456'
        ]));
        
        return json_decode($client->getResponse()->getContent())->access_token;
    }

    private function loadUserFixture($client) {
        // $container = $client->getContainer();
        // $doctrine = $container->get('doctrine');
        // $entityManager = $doctrine->getManager();
        
        // $fixture = new UserFixtures();
        // $fixture->load($entityManager);
    }
}