<?php

use App\Manager\CategoryManager;
use App\Twig\EasyAdminTwigExtension;
use EasyCorp\Bundle\EasyAdminBundle\Configuration\ConfigManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Twig\TwigFunction;

class EasyAdminTwigExtensionTest extends TestCase
{
    public $requestStack;
    public $categoryManager;
    public $configManager;
    public $easyAdminTwigExtension;
    public $propertyAccessor;

    protected function setUp(): void
    {
        DG\BypassFinals::enable();
        $this->configManager = $this->createMock(ConfigManager::class);
        $this->categoryManager = $this->createMock(CategoryManager::class);
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->propertyAccessor = $this->createMock(PropertyAccessor::class);
        $this->easyAdminTwigExtension = new EasyAdminTwigExtension($this->configManager, $this->requestStack, $this->propertyAccessor , $this->categoryManager);
    }

    public function testGetFunctions(){
        $instanceTwig = $this->easyAdminTwigExtension->getFunctions();
        $this->assertInstanceOf(TwigFunction::class ,$instanceTwig[0]);
    }

    /**
     * @dataProvider provideTestGetActionsForItem
     */
    public function testGetActionsForItem($configQuestion, $entityName){

        $request = $this->createMock(Request::class);
        $request->query = $this->createMock(ParameterBag::class);

        $request->query
            ->method('get')
            ->willReturn(5);

        $this->categoryManager
            ->method('hasQuestionInCategory')
            ->willReturn(true);

        $this->requestStack
            ->method('getCurrentRequest')
            ->willReturn($request);

        $this->configManager
            ->expects($this->once())
            ->method('getEntityConfig')
            ->willReturn($configQuestion);

        $actionDelete =$this->easyAdminTwigExtension->getActionsForItem($entityName);
        $this->assertSame('delete', $actionDelete['delete']['name'][0]);
    }

    public function provideTestGetActionsForItem(): array
    {

        $configQuestion = [
            'disabled_actions' =>
                [
                    'test'
                ],
            'edit' =>
                [
                    'actions' =>
                        [
                            'delete' =>
                                [
                                    'name' =>
                                        [
                                            'delete'
                                        ],
                                ],
                        ],
                ],
            'name' =>
                [
                    'Category'
                ],
        ];

        return [
            [$configQuestion, 'TextQuestion'],
            [$configQuestion, 'Category'],
        ];
    }
}