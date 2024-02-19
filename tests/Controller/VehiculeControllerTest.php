<?php

namespace App\Test\Controller;

use App\Entity\Vehicule;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VehiculeControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/crud/vehicule/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Vehicule::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Vehicule index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'vehicule[brandt]' => 'Testing',
            'vehicule[model]' => 'Testing',
            'vehicule[features]' => 'Testing',
            'vehicule[year]' => 'Testing',
            'vehicule[kilometers]' => 'Testing',
            'vehicule[type]' => 'Testing',
            'vehicule[price]' => 'Testing',
            'vehicule[photo]' => 'Testing',
        ]);

        self::assertResponseRedirects('/sweet/food/');

        self::assertSame(1, $this->getRepository()->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Vehicule();
        $fixture->setBrandt('My Title');
        $fixture->setModel('My Title');
        $fixture->setFeatures('My Title');
        $fixture->setYear('My Title');
        $fixture->setKilometers('My Title');
        $fixture->setType('My Title');
        $fixture->setPrice('My Title');
        $fixture->setPhoto('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Vehicule');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Vehicule();
        $fixture->setBrandt('Value');
        $fixture->setModel('Value');
        $fixture->setFeatures('Value');
        $fixture->setYear('Value');
        $fixture->setKilometers('Value');
        $fixture->setType('Value');
        $fixture->setPrice('Value');
        $fixture->setPhoto('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'vehicule[brandt]' => 'Something New',
            'vehicule[model]' => 'Something New',
            'vehicule[features]' => 'Something New',
            'vehicule[year]' => 'Something New',
            'vehicule[kilometers]' => 'Something New',
            'vehicule[type]' => 'Something New',
            'vehicule[price]' => 'Something New',
            'vehicule[photo]' => 'Something New',
        ]);

        self::assertResponseRedirects('/crud/vehicule/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getBrandt());
        self::assertSame('Something New', $fixture[0]->getModel());
        self::assertSame('Something New', $fixture[0]->getFeatures());
        self::assertSame('Something New', $fixture[0]->getYear());
        self::assertSame('Something New', $fixture[0]->getKilometers());
        self::assertSame('Something New', $fixture[0]->getType());
        self::assertSame('Something New', $fixture[0]->getPrice());
        self::assertSame('Something New', $fixture[0]->getPhoto());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Vehicule();
        $fixture->setBrandt('Value');
        $fixture->setModel('Value');
        $fixture->setFeatures('Value');
        $fixture->setYear('Value');
        $fixture->setKilometers('Value');
        $fixture->setType('Value');
        $fixture->setPrice('Value');
        $fixture->setPhoto('Value');

        $this->manager->remove($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/crud/vehicule/');
        self::assertSame(0, $this->repository->count([]));
    }
}
