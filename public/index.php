<?php

use App\Database\Doctrine\Entity\ShortLink;
use App\Database\Doctrine\Entity\User;

    $container = require_once '../src/bootstrap.php';

    $entityManager = $container->get('doctrine');
    $shortener     = $container->get('url_converter');
    $username      = $container->getParam('database.username');

    $userRepository       = $entityManager->getRepository(User::class);
    $shortLinksRepository = $entityManager->getRepository(ShortLink::class);

    $user = $userRepository->findByUsername($username);

    if(isset($_GET['url'])) {
        if(isset($_GET['code'])) {
            $shortener->setShortLink($_GET['code']);
        }

        if(isset($_GET['length'])) {
            $shortener->setShortLinkTength((int) $_GET['length']);
        }

        $shortLinkEntity = new ShortLink($shortener->convert($_GET['url']));

        $user->addShortLink($shortLinkEntity);

        $entityManager->persist($shortLinkEntity);

        $entityManager->persist($user);

        $entityManager->flush();
    }

    echo 'Hello '. $user->getUsername();

    echo '<br>';

    echo 'Your short links:';

    echo '<br>';

    $links = $shortLinksRepository->findBy(['user' => $user]);

    foreach($links as $link) {
        echo '<br>';
        echo '<br>';
        echo 'url: '. $link->getUrl();
        echo '<br>';
        echo 'code: '. $link->getcode();
        echo '<br>';
        echo 'hash: ' . $link->getHash();
    }
