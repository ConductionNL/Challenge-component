<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Deal;
use App\Entity\Entry;
use App\Entity\Pitch;
use App\Entity\PitchStage;
use App\Entity\Proposal;
use App\Entity\Question;
use App\Entity\Tender;
use App\Entity\TenderStage;
use Conduction\CommonGroundBundle\Service\CommonGroundService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ConductionFixtures extends Fixture
{
    private $params;
    /**
     * @var CommonGroundService
     */
    private $commonGroundService;

    public function __construct(ParameterBagInterface $params, CommonGroundService $commonGroundService)
    {
        $this->params = $params;
        $this->commonGroundService = $commonGroundService;
    }

    public function load(ObjectManager $manager)
    {
        if (
            // If build all fixtures is true we build all the fixtures
            !$this->params->get('app_build_all_fixtures') &&
            $this->params->get('app_domain') != 'zuiddrecht.nl' && strpos($this->params->get('app_domain'), 'zuiddrecht.nl') == false &&
            $this->params->get('app_domain') != 'zuid-drecht.nl' && strpos($this->params->get('app_domain'), 'zuid-drecht.nl') == false
        ) {
            return false;
        }

        $id = Uuid::fromString('5f7c339a-bdf1-4b57-8539-be9de88ea24f');
        $tender = new Tender();
        $tender->setName('Test Tender');
        $tender->setDescription('Dit is een test tender.');
        $tender->setSubmitters((array) 'Conduction');
        $tender->setBudget(150000);
        $tender->setKind('Product');
        $tender->setDocuments(['linknaardocument', 'nogeenlinknaardocument']);
        $tender->setSelectionCritera((array)'Moet deze test tender willen bekijken :).');
        $tender->setDateClose(new \DateTime(date('2020-12-06T12:00:01+00:00')));
        $manager->persist($tender);
        $tender->setId($id);
        $manager->persist($tender);
        $manager->flush();
        $tender = $manager->getRepository('App:Tender')->findOneBy(['id'=> $id]);

        $id = Uuid::fromString('2724e604-e1eb-452f-aa3c-dcc278d1ff14');
        $tenderStage1 = new TenderStage();
        $tenderStage1->setName('Inschrijfperiode');
        $tenderStage1->setDescription('Dit is een test tender stage.');
        $tenderStage1->setRequirements(['Minimaal 5 inschrijvingen', 'Het moet 25 augustus geweest zijn']);
        $manager->persist($tenderStage1);
        $tenderStage1->setId($id);
        $manager->persist($tenderStage1);
        $manager->flush();
        $tenderStage1 = $manager->getRepository('App:TenderStage')->findOneBy(['id'=> $id]);

        $tender->setCurrentStage($tenderStage1);
        $manager->persist($tender);
        $manager->flush();

        $id = Uuid::fromString('7bf0dc45-8f86-486a-a1b3-02eeef926022');
        $tenderStage = new TenderStage();
        $tenderStage->setName('Pitchperiode');
        $tenderStage->setDescription('Dit is een test tender stage.');
        $tenderStage->setRequirements(['Minimaal 5 pitches', 'Het moet 25 oktober geweest zijn']);
        $manager->persist($tenderStage);
        $tenderStage->setId($id);
        $manager->persist($tenderStage);
        $manager->flush();
        $tenderStage = $manager->getRepository('App:TenderStage')->findOneBy(['id'=> $id]);

        $id = Uuid::fromString('a0fe307c-2c1d-4a41-9274-4e85ab409585');
        $tenderStage = new TenderStage();
        $tenderStage->setName('Voorstelperiode');
        $tenderStage->setDescription('Dit is een test tender stage.');
        $tenderStage->setRequirements(['Minimaal 2 inschrijvingen', 'Het moet 25 augustus geweest zijn']);
        $manager->persist($tenderStage);
        $tenderStage->setId($id);
        $manager->persist($tenderStage);
        $manager->flush();
        $tenderStage = $manager->getRepository('App:TenderStage')->findOneBy(['id'=> $id]);

        $id = Uuid::fromString('6d62a077-87ce-4f21-936c-331c369ac601');
        $tenderStage = new TenderStage();
        $tenderStage->setName('Afsluitperiode');
        $tenderStage->setDescription('Dit is een test tender stage.');
        $tenderStage->setRequirements(['Er moet een deal gemaakt zijn', 'De tender moet beëndigd worden']);
        $manager->persist($tenderStage);
        $tenderStage->setId($id);
        $manager->persist($tenderStage);
        $manager->flush();
        $tenderStage = $manager->getRepository('App:TenderStage')->findOneBy(['id'=> $id]);

        $id = Uuid::fromString('137f7d74-f131-44d8-9997-990c5193d227');
        $entry = new Entry();
        $entry->setSubmitters(['Henk']);
        $entry->setDateOfEntry(new \DateTime(date('2020-7-07T12:00:01+00:00')));
        $entry->setTender($tender);
        $manager->persist($entry);
        $entry->setId($id);
        $manager->persist($entry);
        $manager->flush();
        $entry = $manager->getRepository('App:Entry')->findOneBy(['id'=> $id]);

        $tender->addEntry($entry);
        $manager->persist($tender);
        $manager->flush();

        $id = Uuid::fromString('d969b30b-41cc-455f-bdc7-4d5e8969bbbb');
        $question = new Question();
        $question->setName('Eerste 5 getallen van PI');
        $question->setDescription('Dit is een test vraag.');
        $question->setSubmitters(['Wilco Louwerse']);
        $question->setQuestion('Wat zijn de eerste 5 getallen van PI, na de comma?');
        $question->setStatus('answered');
        $question->setEntry($entry);
        $manager->persist($question);
        $question->setId($id);
        $manager->persist($question);
        $manager->flush();
        $question = $manager->getRepository('App:Question')->findOneBy(['id'=> $id]);

        $id = Uuid::fromString('f554cbe0-33e9-469c-aee4-a6e7c9e1e096');
        $answer = new Answer();
        $answer->setName('Antwoord voor een vraag');
        $answer->setSubmitters(['Wilco Louwerse']);
        $answer->setAnswer('3,14159');
        $answer->setQuestion($question);
        $manager->persist($answer);
        $answer->setId($id);
        $manager->persist($answer);
        $manager->flush();
        $answer = $manager->getRepository('App:Answer')->findOneBy(['id'=> $id]);

        $tender->addQuestion($question);
        $entry->addQuestion($question);
        $manager->persist($entry);
        $manager->persist($tender);
        $manager->flush();

        $id = Uuid::fromString('9ba1cbf5-470f-4933-9837-31f81c64f1cb');
        $pitch = new Pitch();
        $pitch->setName('Pitch van Henk');
        $pitch->setDescription('Dit is een test pitch.');
        $pitch->setSubmitter('Henk');
        $pitch->setRequiredBudget(100000);
        $pitch->setTender($tender);
        $pitch->setDateSubmitted(new \DateTime(date('2020-7-16T12:00:01+00:00')));
        $manager->persist($pitch);
        $pitch->setId($id);
        $manager->persist($pitch);
        $manager->flush();
        $pitch = $manager->getRepository('App:Pitch')->findOneBy(['id'=> $id]);

        $tender->addPitch($pitch);
        $manager->persist($tender);
        $manager->flush();

        $id = Uuid::fromString('ae5934e7-3635-48e9-9ed7-df7d22bebbcf');
        $pitchStage = new PitchStage();
        $pitchStage->setName('Voorbereidingsperiode');
        $pitchStage->setDescription('Dit is een test pitch stage.');
        $pitchStage->setRequirements(['Het moet 2020-8-20 geweest zijn']);
        $manager->persist($pitchStage);
        $pitchStage->setId($id);
        $manager->persist($pitchStage);
        $manager->flush();
        $pitchStage = $manager->getRepository('App:PitchStage')->findOneBy(['id'=> $id]);

        $pitch->setCurrentStage($pitchStage);
        $manager->persist($pitch);
        $manager->flush();

        $id = Uuid::fromString('7daf2eaf-be74-4459-8514-62b211f05dea');
        $proposal = new Proposal();
        $proposal->setName('Voorstel van Henk');
        $proposal->setDescription('Dit is een test proposal.');
        $proposal->setStatus('In afwachting');
        $proposal->setPitch($pitch);
        $proposal->setTender($tender);
        $proposal->setDocuments(['linkdocumentje', 'linknaardocumentje']);
        $manager->persist($proposal);
        $proposal->setId($id);
        $manager->persist($proposal);
        $manager->flush();
        $proposal = $manager->getRepository('App:Proposal')->findOneBy(['id'=> $id]);

        $id = Uuid::fromString('b0d31d3f-ba2f-4dbe-a71b-fa1957e0d6ee');
        $deal = new Deal();
        $deal->setName('Tender Test');
        $deal->setDescription('Dit is een test deal.');
        $deal->setContractors(['Henk']);
        $deal->setProposal($proposal);
        $manager->persist($deal);
        $deal->setId($id);
//        $deal->setTender($tender);
        $manager->persist($deal);
        $manager->flush();
        $deal = $manager->getRepository('App:Deal')->findOneBy(['id'=> $id]);

        $tender->setDeal($deal);
        $manager->persist($tender);
        $manager->flush();
    }
}
