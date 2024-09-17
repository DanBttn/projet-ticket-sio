<?php

namespace App\Tests\Controller;

use App\Entity\Incident;
use App\Repository\IncidentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class IncidentControllerTest extends WebTestCase{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/incident/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Incident::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Incident index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'incident[reference]' => 'Testing',
            'incident[createdAt]' => 'Testing',
            'incident[processedAt]' => 'Testing',
            'incident[resolvedAt]' => 'Testing',
            'incident[rejectedAt]' => 'Testing',
            'incident[description]' => 'Testing',
            'incident[reporterEmail]' => 'Testing',
            'incident[status]' => 'Testing',
            'incident[priority]' => 'Testing',
            'incident[types]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Incident();
        $fixture->setReference('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setProcessedAt('My Title');
        $fixture->setResolvedAt('My Title');
        $fixture->setRejectedAt('My Title');
        $fixture->setDescription('My Title');
        $fixture->setReporterEmail('My Title');
        $fixture->setStatus('My Title');
        $fixture->setPriority('My Title');
        $fixture->setTypes('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Incident');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Incident();
        $fixture->setReference('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setProcessedAt('Value');
        $fixture->setResolvedAt('Value');
        $fixture->setRejectedAt('Value');
        $fixture->setDescription('Value');
        $fixture->setReporterEmail('Value');
        $fixture->setStatus('Value');
        $fixture->setPriority('Value');
        $fixture->setTypes('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'incident[reference]' => 'Something New',
            'incident[createdAt]' => 'Something New',
            'incident[processedAt]' => 'Something New',
            'incident[resolvedAt]' => 'Something New',
            'incident[rejectedAt]' => 'Something New',
            'incident[description]' => 'Something New',
            'incident[reporterEmail]' => 'Something New',
            'incident[status]' => 'Something New',
            'incident[priority]' => 'Something New',
            'incident[types]' => 'Something New',
        ]);

        self::assertResponseRedirects('/incident/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getReference());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getProcessedAt());
        self::assertSame('Something New', $fixture[0]->getResolvedAt());
        self::assertSame('Something New', $fixture[0]->getRejectedAt());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getReporterEmail());
        self::assertSame('Something New', $fixture[0]->getStatus());
        self::assertSame('Something New', $fixture[0]->getPriority());
        self::assertSame('Something New', $fixture[0]->getTypes());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Incident();
        $fixture->setReference('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setProcessedAt('Value');
        $fixture->setResolvedAt('Value');
        $fixture->setRejectedAt('Value');
        $fixture->setDescription('Value');
        $fixture->setReporterEmail('Value');
        $fixture->setStatus('Value');
        $fixture->setPriority('Value');
        $fixture->setTypes('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/incident/');
        self::assertSame(0, $this->repository->count([]));
    }
}
