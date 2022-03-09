<?php

namespace App\Controller;

use App\Entity\Job;
use App\Form\JobType;
use App\Repository\JobRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class JobsController extends AbstractController
{
    #[Route('/', name: 'jobs')]
    public function index(JobRepository $jobsRepository, Request $request): Response
    {

        /*//Inserting initial data in db from data.json
        $string = file_get_contents('../data/data.json');
        $json_d = json_decode($string, true);
        //dump($json_d);

        foreach ($json_d as $key => $value) {
            $job = new Job();
            $job->setData($value);
            $job->setPostedAt();
            $job->setUserId($this->get('session')->get('loggedinUser')->getId());
            $job->setLogo('default-default-logo.png');
            $em = $this->getDoctrine()->getManager();
            $em->persist($job);
            $em->flush();
        }*/
        $keyword = $request->query->get("keyword");
        $location = $request->query->get('location');


        $jobs = $jobsRepository->findAll();

        if ($keyword && $location) {
            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                "SELECT j
            FROM App\Entity\Job j
            WHERE j.position like :keyword
            AND j.location  like :location"
            )->setParameters(array('keyword' => '%' . $keyword . '%', 'location' => $location));
            $jobs = $query->getResult();
            return $this->render('jobs/index.html.twig', [
                'jobs' => $jobs
            ]);
        }

        if ($keyword) {
            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                "SELECT j
            FROM App\Entity\Job j
            WHERE j.position like :keyword"
            )->setParameter('keyword', '%' . $keyword . '%');
            $jobs = $query->getResult();
        }
        if ($location) {
            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                "SELECT j
            FROM App\Entity\Job j
            WHERE j.location like :location"
            )->setParameter('location', '%' . $location . '%');
            $jobs = $query->getResult();
        }


        return $this->render('jobs/index.html.twig', [
            'jobs' => $jobs
        ]);
    }


    #[Route('/jobs/new', name: 'newJob')]
    public function createJob(JobRepository $jobsRepository, Request $request, SluggerInterface $slugger): Response
    {
        $job = new Job();
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->get('session')->get('loggedinUser')->getId();
            $job = $form->getData();
            $job->setUserId($user);
            //Uploading logo
            $logoImg = $form->get('logo')->getData();
            if ($logoImg) {
                $originalFilename = pathinfo($logoImg->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $logoImg->guessExtension();
                $logoImg->move(
                    $this->getParameter('logos_directory'),
                    $newFilename
                );
                $job->setLogo($newFilename);
            }
            $job->setPostedAt();
            $em = $this->getDoctrine()->getManager();
            $em->persist($job);
            $em->flush();
            return $this->redirect('/');
        }
        return $this->renderForm('jobs/new-job.html.twig', ['form' => $form]);
    }

    #[Route('/users/{userId}/jobs/{jobId}', name: 'editJob')]
    public function editJob(JobRepository $jobsRepository, Request $request): Response
    {
        $jobId = $request->get('jobId');
        $job = $jobsRepository->findOneBy(['id' => $jobId]);
        //dump($job);
        return $this->render('jobs/jobDetails.html.twig', [
            'job' => $job
        ]);
    }

    #[Route('/users/{id}/jobs', name: 'jobsByUser')]
    public function getJobsByUser(JobRepository $jobsRepository, Request $request): Response
    {
        $id = $request->get('id');
        $jobs = $jobsRepository->findBy(['userId' => $id]);
        //dump($job);
        return $this->render('jobs/index.html.twig', [
            'jobs' => $jobs
        ]);
    }

    #[Route('/jobs/delete/{id}', name: 'deleteJob')]
    public function deleteJob(JobRepository $jobsRepository, Request $request): Response
    {
        $id = $request->get('id');
        $job = $jobsRepository->findOneBy(['id' => $id]);
        $em = $this->getDoctrine()->getManager();
        $em->remove($job);
        $em->flush();
        return $this->redirect('/');
    }

    #[Route('/jobs/update/{id}', name: 'updateJob')]
    public function updateJob(JobRepository $jobsRepository, Request $request, SluggerInterface $slugger): Response
    {
        $id = $request->get('id');
        $job = $jobsRepository->findOneBy(['id' => $id]);
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->get('session')->get('loggedinUser')->getId();
            $job = $form->getData();
            $job->setUserId($user);
            //Uploading logo
            $logoImg = $form->get('logo')->getData();
            if ($logoImg) {
                $originalFilename = pathinfo($logoImg->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $logoImg->guessExtension();
                $logoImg->move(
                    $this->getParameter('logos_directory'),
                    $newFilename
                );
                $job->setLogo($newFilename);
            }
            $job->setPostedAt();
            $em = $this->getDoctrine()->getManager();
            $em->persist($job);
            $em->flush();
            return $this->redirect('/jobs/' . $job->getId());
        }
        return $this->renderForm('jobs/new-job.html.twig', ['form' => $form]);
    }

    #[Route('/jobs/{id}', name: 'jobDetails')]
    public function jobDetailsController(JobRepository $jobsRepository, Request $request): Response
    {
        $id = $request->get('id');
        $job = $jobsRepository->findOneBy(['id' => $id]);
        //dump($job);
        return $this->render('jobs/jobDetails.html.twig', ['job' => $job]);
    }

    #[Route('/json/jobs', name: 'jobsJson')]
    public function returnJson(JobRepository $jobsRepository, Request $request): Response
    {

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT j
    FROM App\Entity\Job j'
        );
        $jobs = $query->getArrayResult();

        return new JsonResponse($jobs);


    }
}
