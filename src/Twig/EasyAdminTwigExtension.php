<?php

namespace App\Twig;

use App\Manager\CategoryManager;
use EasyCorp\Bundle\EasyAdminBundle\Configuration\ConfigManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EasyAdminTwigExtension extends AbstractExtension
{
    private $configManager;
    private $categoryManager;
    protected $requestStack;

    public function __construct(ConfigManager $configManager, RequestStack $requestStack, PropertyAccessorInterface $propertyAccessor, CategoryManager $categoryManager)
    {
        $this->configManager = $configManager;
        $this->categoryManager = $categoryManager;
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('easyadmin_get_actions_for_edit_item', [$this, 'getActionsForItem']),
        ];
    }

    public function getActionsForItem(string $entityName): array
    {
        $view = 'edit';
        try {
            $entityConfig = $this->configManager->getEntityConfig($entityName);
        } catch (\Exception $e) {
            return [];
        }
        $disabledActions = $entityConfig['disabled_actions'];
        $viewActions = $entityConfig[$view]['actions'];

        if ('Category' == $entityName) {
            $request = $this->requestStack->getCurrentRequest();
            $getQuestionByCategory = $this->categoryManager->hasQuestionInCategory($request->query->get('id'));
        }
        if (isset($getQuestionByCategory) && true == $getQuestionByCategory) {
            $actionsExcludedForItems = [
                $view => ['delete'],
            ];
        } else {
            $actionsExcludedForItems = [
                $view => [],
            ];
        }
        $excludedActions = $actionsExcludedForItems[$view];

        return array_filter($viewActions, function ($action) use ($excludedActions, $disabledActions) {
            return !\in_array($action['name'], $excludedActions) && !\in_array($action['name'], $disabledActions);
        });
    }
}
