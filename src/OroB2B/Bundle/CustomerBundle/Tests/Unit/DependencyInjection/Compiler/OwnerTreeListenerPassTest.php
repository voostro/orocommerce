<?php

namespace OroB2B\Bundle\CustomerBundle\Tests\Unit\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use OroB2B\Bundle\CustomerBundle\DependencyInjection\Compiler\OwnerTreeListenerPass;

class OwnerTreeListenerPassTest extends \PHPUnit_Framework_TestCase
{
    public function testProcess()
    {
        $listenerDefinition = $this->getMockBuilder('Symfony\Component\DependencyInjection\Definition')
            ->disableOriginalConstructor()
            ->getMock();

        $listenerDefinition->expects($this->exactly(2))
            ->method('addMethodCall')
            ->with('addSupportedClass', $this->isType('array'));

        /** @var ContainerBuilder|\PHPUnit_Framework_MockObject_MockObject $containerBuilder */
        $containerBuilder = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerBuilder')
            ->disableOriginalConstructor()
            ->getMock();

        $containerBuilder->expects($this->once())
            ->method('getDefinition')
            ->with(OwnerTreeListenerPass::LISTENER_SERVICE)
            ->will($this->returnValue($listenerDefinition));

        $containerBuilder->expects($this->exactly(2))
            ->method('getParameter')
            ->with($this->isType('string'));

        $compilerPass = new  OwnerTreeListenerPass();
        $compilerPass->process($containerBuilder);
    }
}
