<?php

namespace App\EventSubscriber;

use App\Repository\SettingRepository;
use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{
	public function __construct(
		private Environment $twig,
		private SettingRepository $settingRepository,
		private UserRepository $userRepository
	) {
	}

	public function onControllerEvent(ControllerEvent $event): void
	{
		// Add a Global Var to Twig on Controller Event 
		// to not call SettingRepository in every controller
		$this->twig->addGlobal('setting', $this->settingRepository->findOneBy([], []));
		$this->twig->addGlobal('user', $this->userRepository->findOneBy([], []));
	}

	public static function getSubscribedEvents(): array
	{
		return [
			ControllerEvent::class => 'onControllerEvent',
		];
	}
}
