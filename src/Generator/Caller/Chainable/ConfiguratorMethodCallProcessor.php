<?php

/*
 * This file is part of the Alice package.
 *
 * (c) Nelmio <hello@nelm.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Nelmio\Alice\Generator\Caller\Chainable;

use Nelmio\Alice\Definition\MethodCall\ConfiguratorMethodCall;
use Nelmio\Alice\Definition\MethodCall\SimpleMethodCall;
use Nelmio\Alice\Definition\MethodCallInterface;
use Nelmio\Alice\Definition\Object\SimpleObject;
use Nelmio\Alice\Definition\ValueInterface;
use Nelmio\Alice\FixtureInterface;
use Nelmio\Alice\Generator\Caller\CallProcessorAwareInterface;
use Nelmio\Alice\Generator\Caller\CallProcessorInterface;
use Nelmio\Alice\Generator\Caller\ChainableCallProcessorInterface;
use Nelmio\Alice\Generator\CallerInterface;
use Nelmio\Alice\Generator\GenerationContext;
use Nelmio\Alice\Generator\ResolvedFixtureSet;
use Nelmio\Alice\Generator\ValueResolverAwareInterface;
use Nelmio\Alice\Generator\ValueResolverInterface;
use Nelmio\Alice\IsAServiceTrait;
use Nelmio\Alice\ObjectInterface;
use Nelmio\Alice\Throwable\Exception\Generator\Resolver\ResolverNotFoundExceptionFactory;
use Nelmio\Alice\Throwable\Exception\Generator\Resolver\UnresolvableValueDuringGenerationExceptionFactory;
use Nelmio\Alice\Throwable\InstantiationThrowable;
use Nelmio\Alice\Throwable\ResolutionThrowable;

final class ConfiguratorMethodCallProcessor implements ChainableCallProcessorInterface, CallProcessorAwareInterface
{
    use IsAServiceTrait;

    /**
     * @var CallProcessorInterface|null
     */
    private $processor;

    public function __construct(CallProcessorInterface $processor = null)
    {
        $this->processor = $processor;
    }

    /**
     * @inheritdoc
     */
    public function withProcessor(CallProcessorInterface $processor): self
    {
        return new self($processor);
    }

    /**
     * @inheritdoc
     */
    public function canProcess(MethodCallInterface $methodCall): bool
    {
        return $methodCall instanceof ConfiguratorMethodCall;
    }

    /**
     * @inheritdoc
     */
    public function process(
        ObjectInterface $object,
        ResolvedFixtureSet $fixtureSet,
        GenerationContext $context,
        MethodCallInterface $methodCall
    ): ResolvedFixtureSet
    {
        if (null === $this->processor) {
            throw new \LogicException('TODO');
        }

        $context->markRetrieveCallResult();

        $fixtureSet = $this->processor->process(
            $object,
            $fixtureSet,
            $context,
            $methodCall->getOriginalMethodCall()
        );

        $context->unmarkRetrieveCallResult();

        return $fixtureSet;
    }
}
