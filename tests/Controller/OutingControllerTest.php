<?php

namespace App\Tests\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class OutingControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ?Utilisateur $user = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();

        /** @var UtilisateurRepository $userRepository */
        $userRepository = self::getContainer()->get(UtilisateurRepository::class);

        // Récupérer un utilisateur existant pour les tests
        $this->user = $userRepository->findOneBy([]);
    }

    public function testAddOutingPageIsSuccessful(): void
    {
        if ($this->user) {
            $this->client->loginUser($this->user);
        }

        $crawler = $this->client->request('GET', '/sortie/add');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('form[name="outing"]');
    }

    public function testRedirectIfNotLoggedIn(): void
    {
        $anonymousClient = static::createClient();
        $anonymousClient->request('GET', '/sortie/add');

        self::assertResponseRedirects('/login');
    }

    public function testSubmitFormSave(): void
    {
        if (!$this->user) {
            self::markTestSkipped('No user available for login.');
        }

        $this->client->loginUser($this->user);

        $crawler = $this->client->request('GET', '/sortie/add');

        $form = $crawler->selectButton('Enregistrer')->form([
            'outing[name]' => 'Test Sortie',
            'outing[startDateTime]' => '2025-12-01 14:00',
            'outing[registrationLimitDate]' => '2025-11-25',
            'outing[nbMaxRegistration]' => 20,
            'outing[duration]' => 90,
            'outing[outingInfos]' => 'Test description',
            'outing[campus]' => 1,   // Assure-toi qu’un campus existe avec cet id
            'outing[location]' => 1, // Assure-toi qu’un lieu existe avec cet id
        ]);

        $form->setValues(['action' => 'save']);

        $this->client->submit($form);

        // Vérifier la redirection vers la page principale
        self::assertResponseRedirects('/');

        // Suivre la redirection pour vérifier le flash message
        $this->client->followRedirect();
        self::assertSelectorTextContains('.alert-success', 'Sortie ajoutée avec succès');
    }

    public function testSubmitFormPublish(): void
    {
        if (!$this->user) {
            self::markTestSkipped('No user available for login.');
        }

        $this->client->loginUser($this->user);

        $crawler = $this->client->request('GET', '/sortie/add');

        $form = $crawler->selectButton('Publier la sortie')->form([
            'outing[name]' => 'Test Sortie Publiée',
            'outing[startDateTime]' => '2025-12-10 10:00',
            'outing[registrationLimitDate]' => '2025-12-01',
            'outing[nbMaxRegistration]' => 30,
            'outing[duration]' => 120,
            'outing[outingInfos]' => 'Test description publish',
            'outing[campus]' => 1,
            'outing[location]' => 1,
        ]);

        $form->setValues(['action' => 'publish']);

        $this->client->submit($form);

        self::assertResponseRedirects('/');
        $this->client->followRedirect();
        self::assertSelectorTextContains('.alert-success', 'Sortie ajoutée avec succès');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->client = null;
        $this->user = null;
    }
}
