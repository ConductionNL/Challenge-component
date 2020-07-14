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
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ZuiddrechtFixtures extends Fixture
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
            !$this->params->get('app_build_all_fixtures') &&
            $this->params->get('app_domain') != 'zuiddrecht.nl' && strpos($this->params->get('app_domain'), 'zuiddrecht.nl') == false &&
            $this->params->get('app_domain') != 'zuid-drecht.nl' && strpos($this->params->get('app_domain'), 'zuid-drecht.nl') == false
        ) {
            return false;
        }

        $id = Uuid::fromString('8191183b-ae8e-4d7e-b52e-a3517313491c');
        $tender = new Tender();
        $tender->setName('Zwembad in Zuid-Drecht');
        $tender->setDescription('Dit is een test tender.');
        $tender->setSubmitters((array) 'Gemeente Zuid-Drecht');
        $tender->setBudget(150000);
        $tender->setKind('Product');
        $tender->setDocuments(['linknaardocument', 'nogeenlinknaardocument']);
        $tender->setSelectionCritera('Moet 4 jaar ervaren zijn in het ontwerpen van zwembaden.');
        $tender->setDateClose(new \DateTime(date('2020-12-06T12:00:01+00:00')));
        $manager->persist($tender);
        $tender->setId($id);
        $manager->persist($tender);
        $manager->flush();
        $tender = $manager->getRepository('App:Tender')->findOneBy(['id'=> $id]);

        $id = Uuid::fromString('9269a257-99f2-478e-b19a-c0342e0b6aad');
        $tenderStage1 = new TenderStage();
        $tenderStage1->setName('Inschrijfperiode');
        $tenderStage1->setDescription('Dit is een test tender stage.');
        $tenderStage1->setRequirements(['Minimaal 10 inschrijvingen', 'Het moet 20 augustus geweest zijn']);
        $manager->persist($tenderStage1);
        $tenderStage1->setId($id);
        $manager->persist($tenderStage1);
        $manager->flush();
        $tenderStage1 = $manager->getRepository('App:TenderStage')->findOneBy(['id'=> $id]);

        $tender->setCurrentStage($tenderStage1);
        $manager->persist($tender);
        $manager->flush();

        $id = Uuid::fromString('8df25ec9-c01a-4848-b9e2-e50bec4c91aa');
        $tenderStage = new TenderStage();
        $tenderStage->setName('Pitchperiode');
        $tenderStage->setDescription('Dit is een test tender stage.');
        $tenderStage->setRequirements(['Minimaal 10 pitches', 'Het moet 20 oktober geweest zijn']);
        $manager->persist($tenderStage);
        $tenderStage->setId($id);
        $manager->persist($tenderStage);
        $manager->flush();
        $tenderStage = $manager->getRepository('App:TenderStage')->findOneBy(['id'=> $id]);

        $id = Uuid::fromString('b5027fc5-8d81-4d44-9819-2c629730159c');
        $tenderStage = new TenderStage();
        $tenderStage->setName('Voorstelperiode');
        $tenderStage->setDescription('Dit is een test tender stage.');
        $tenderStage->setRequirements(['Minimaal 4 inschrijvingen', 'Het moet 20 augustus geweest zijn']);
        $manager->persist($tenderStage);
        $tenderStage->setId($id);
        $manager->persist($tenderStage);
        $manager->flush();
        $tenderStage = $manager->getRepository('App:TenderStage')->findOneBy(['id'=> $id]);

        $id = Uuid::fromString('eccd4397-0230-4ad5-bb41-05ccb7a00a63');
        $tenderStage = new TenderStage();
        $tenderStage->setName('Afsluitperiode');
        $tenderStage->setDescription('Dit is een test tender stage.');
        $tenderStage->setRequirements(['Er moet een deal gemaakt zijn', 'De tender moet beëndigd worden']);
        $manager->persist($tenderStage);
        $tenderStage->setId($id);
        $manager->persist($tenderStage);
        $manager->flush();
        $tenderStage = $manager->getRepository('App:TenderStage')->findOneBy(['id'=> $id]);

        $id = Uuid::fromString('181dbbb2-ea6b-4763-ae9c-5f470c2bbe26');
        $entry = new Entry();
        $entry->setName('Inschrijving van Swimming Pool Enterprise');
        $entry->setDescription('Dit is een test entry.');
        $entry->setSubmitters(['Swimming Pool Enterprise']);
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

        $id = Uuid::fromString('1e6dc5d8-f81e-47eb-9288-4a058075a2e0');
        $question = new Question();
        $question->setName('Grootte van het zwembad');
        $question->setDescription('Dit is een test vraag.');
        $question->setSubmitters(['Barry Brands']);
        $question->setQuestion('Is de grootte van het gevraagde zwembad bespreekbaar?');
        $question->setStatus('answered');
        $question->setEntry($entry);
        $manager->persist($question);
        $question->setId($id);
        $manager->persist($question);
        $manager->flush();
        $question = $manager->getRepository('App:Question')->findOneBy(['id'=> $id]);

        $id = Uuid::fromString('e48c893e-558c-4f20-88b0-58a8b1e87a78');
        $answer = new Answer();
        $answer->setName('Antwoord voor een vraag');
        $answer->setSubmitters(['Barry Brands']);
        $answer->setAnswer('Ja het grootte van het zwembad is te bespreken.');
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

        $id = Uuid::fromString('2ad4af21-ddc8-4264-b343-386307dbb12e');
        $pitch = new Pitch();
        $pitch->setName('Pitch van Swimming Pool Enterprise');
        $pitch->setDescription('Dit is een test pitch.');
        $pitch->setSubmitter($this->commonGroundService->cleanUrl(['component'=>'brp', 'type'=>'ingeschrevenpersonen/uuid', 'id'=>'f3ff6653-12be-48bc-afb6-42038576eb57']));
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

        $id = Uuid::fromString('0ae32889-858c-402d-aea6-066a44ba1aa9');
        $pitchStage = new PitchStage();
        $pitchStage->setName('Voorbereidingsperiode');
        $pitchStage->setDescription('Dit is een test pitch stage.');
        $pitchStage->setRequirements(['Het moet 2020-8-12 geweest zijn']);
        $manager->persist($pitchStage);
        $pitchStage->setId($id);
        $manager->persist($pitchStage);
        $manager->flush();
        $pitchStage = $manager->getRepository('App:PitchStage')->findOneBy(['id'=> $id]);

        $pitch->setCurrentStage($pitchStage);
        $manager->persist($pitch);
        $manager->flush();

        $id = Uuid::fromString('62364034-28d6-450e-b447-e8752f73a417');
        $proposal = new Proposal();
        $proposal->setName('Voorstel van Swimming Pool Enterprise');
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

        $id = Uuid::fromString('750600de-2115-4b78-88b9-1f45b35cc90f');
        $deal = new Deal();
        $deal->setName('Zwembad Zuid-Drecht');
        $deal->setDescription('Dit is een test deal.');
        $deal->setContractors(['Swimming Pool Enterprise']);
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
