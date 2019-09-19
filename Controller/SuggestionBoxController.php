<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Module as Module;
use AppBundle\Entity\Suggestion as Suggestion;
use AppBundle\Entity\Rate as Rate;

class SuggestionBoxController extends Controller
{
    protected $modules;
    protected $locale;
    protected $user;

    public function __construct()
    {
        //$this->locale = $request->getLocale();
    }

    /**
     * Index page
     * @Route("/suggestions/{page}")
     */
    public function indexAction(Request $request, $page = 1)
    {
        $limit = 15;
        $thisPage = $page;
        $suggestionsDoctrine = $this->getDoctrine()->getRepository(Suggestion::class);
        $suggestions = $suggestionsDoctrine->fetchSuggestions($page);
        $total = $suggestions->count();
        $suggestions = $suggestions->getIterator();
        $user = $this->getUser();
        $is_admin = in_array('ROLE_SUPER_ADMIN', $user->getRoles());
        $suggestionsById = $suggestionsDoctrine->fetchSuggestion($user->getId());
        foreach ($suggestions as $index => $suggestion) {
            $sid = $suggestion[0]->getId();
            $suggestions[$index]['user_rating'] = 0;
            foreach($suggestionsById as $key => $value) {
                if ($value['id'] == $sid) {
                    $suggestions[$index]['user_rating'] = $value['rate'];
                }
            }
        }
        $maxPages = ceil($total / $limit);
        $translate = $this->get('translator');
        $this->locale = $request->getLocale();
        $repository = $this->getDoctrine()->getRepository(Module::class);
        $this->modules = $repository->findBy(['enabled' => true]);
        $indexUrl = $this->getUrl($request);
        return $this->render('AppBundle:SuggestionBox:index.html.twig', [
                'modules'       => $this->modules,
                'locale'        => $this->locale,
                'user_name'     => $this->getUser()->getUserName(),
                'suggestions'   => $suggestions,
                'is_admin'      => $is_admin,
                'thisPage'      => $thisPage,
                'maxPages'      => $maxPages,
                'indexUrl'      => $indexUrl,
                'header'        => $translate->trans('module.suggest_box')
            ]);
    }

    /**
     * Handle add suggestion action
     * @Route("/suggestion/add")
     */
    public function addSuggestionAction(Request $request)
    {
        $suggest = new Suggestion();
        $user = $this->getUser();
        if ($request->get('content')) {
            $em = $this->getDoctrine()->getManager();
            $suggest->setContent($request->get('content'));
            $suggest->setCategory($request->get('category'));
            $suggest->setStatus(1);
            $suggest->setSuggestStatus(1);
            $suggest->setPlanComplete(null);
            $suggest->setUserId($user->getId());
            $em->persist($suggest);
            $em->flush();
        }

        return $this->redirect($this->generateUrl(
            $this->getUrl($request)
            ));
    }

    /**
     * Handle update suggestion
     * @Route("/suggestion/update/{id}")
     */
    public function updateSuggestionAction(Request $request,$id)
    {


        $user = $this->getUser();

        if ($request->get('content')) {
            $em = $this->getDoctrine()->getManager();
            $suggestion = $em->getRepository(Suggestion::class)->find($id);

            if (!$suggestion) {
                throw $this->createNotFoundException(
                    'No suggestion found for id '.$id
                );
            }

            $suggestion->setContent($request->get('content'));
            $suggestion->setCategory($request->get('category'));
            /* $suggestion->setStatus('status'); */
            $suggestion->setSuggestStatus($request->get('suggestion_status'));
            $suggestion->setPlanComplete(new \DateTime($request->get('suggestion_plan')));
            $suggestion->setUserId($user->getId());
            $em->persist($suggestion);
            $em->flush();
        }

        return $this->redirect($this->generateUrl(
            $this->getUrl($request)
        ));
    }

    /**
     * Change suggestion status
     * @Route("/suggestion/updatesuggeststatus/{id}")
     */
    public function updateSuggestionStatusAction(Request $request,$id)
    {


        $user = $this->getUser();

        if ($request->get('suggestion_status')) {
            $em = $this->getDoctrine()->getManager();
            $suggestion = $em->getRepository(Suggestion::class)->find($id);

            if (!$suggestion) {
                throw $this->createNotFoundException(
                    'No suggestion found for id '.$id
                );
            }

            $suggestion->setSuggestStatus($request->get('suggestion_status'));

            $em->persist($suggestion);
            $em->flush();
        }
        return;
    }

    /**
     * Change suggestion plan
     *
     * @Route("/suggestion/updatesuggestplan/{id}")
     */
    public function updateSuggestionPlanAction(Request $request,$id)
    {

        if ($request->get('suggestion_plan')) {
            $em = $this->getDoctrine()->getManager();
            $suggestion = $em->getRepository(Suggestion::class)->find($id);

            if (!$suggestion) {
                throw $this->createNotFoundException(
                    'No suggestion found for id '.$id
                );
            }

            $suggestion->setPlanComplete(new \DateTime($request->get('suggestion_plan')));

            $em->persist($suggestion);
            $em->flush();
        }
        return;
    }

    /**
     * Rate suggestion
     *
     * @Route("/suggestion/rate")
     */
    public function rateSuggestionAction(Request $request)
    {
        $rate = new Rate();
        $user = $this->getUser();
        if ($request->get('suggest_id') && $request->get('score')) {
            $em = $this->getDoctrine()->getManager();
            $suggest = $em->getRepository(Suggestion::class)->find($request->get('suggest_id'));
            $rate->setRate($request->get('score'));
            $rate->setSuggestion($suggest);
            $rate->setUserId($user->getId());
            $em->persist($rate);
            $em->flush();
        }

        return $this->redirect($this->generateUrl(
            $this->getUrl($request)
            ));
    }

    protected function getUrl(Request $request)
    {
        return 'app_suggestionbox_index';
    }
}
