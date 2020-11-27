<?php

namespace App\Tests\ParamConverter;

use App\Model\GameModel;
use App\Model\PlayerModel;
use App\Model\RedisStorageInterface;
use App\ParamConverter\RedisParamConverter;
use App\Repository\AbstractRepository;
use PHPUnit\Framework\TestCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RedisParamConverterTest extends TestCase
{
    private ParamConverter $configuration;
    private AbstractRepository $abstractRepository;

    public function setUp()
    {
        $this->configuration = new ParamConverter(['class' => 'App\Model\GameModel', 'name' => 'game']);
        $this->abstractRepository = $this->createMock(AbstractRepository::class);
    }

    public function testApply()
    {
        $gameModel = new GameModel();
        $request = new Request([], [], ['uuid' => '1eb30c98-b094-6396-b826-fd0c21db3712']);
        $this->abstractRepository->method('find')
            ->willReturn(serialize($gameModel));

        $this->abstractRepository->method('exist')
            ->willReturn(true);
        $redisParamConverter = new RedisParamConverter([], $this->abstractRepository);
        $redisParamConverter->apply($request, $this->configuration);

        $this->assertInstanceOf(RedisStorageInterface::class, $request->attributes->get('game'));
    }

    public function testNotExistApply()
    {
        $request = new Request([], [], ['uuid' => '1eb30c98-b094-6396-b826-fd0c21db3712']);
        $this->abstractRepository->method('exist')
            ->willReturn(false);
        $redisParamConverter = new RedisParamConverter([], $this->abstractRepository);
        $this->expectException(NotFoundHttpException::class);
        $redisParamConverter->apply($request, $this->configuration);
        $this->assertNull($request->attributes->get('game'));
    }

    public function testBadIdentifierApply()
    {
        $request = new Request();
        $redisParamConverter = new RedisParamConverter([], $this->abstractRepository);
        $this->expectException(\LogicException::class);
        $redisParamConverter->apply($request, $this->configuration);
        $this->assertNull($request->attributes->get('game'));
    }

    public function testSupports()
    {
        $this->abstractRepository = $this->createMock(AbstractRepository::class);
        $redisParamConverter = new RedisParamConverter([new GameModel()], $this->abstractRepository);
        $this->assertTrue($redisParamConverter->supports($this->configuration));
    }

    public function testNotSupports()
    {
        $this->abstractRepository = $this->createMock(AbstractRepository::class);
        $redisParamConverter = new RedisParamConverter([new PlayerModel()], $this->abstractRepository);
        $this->assertFalse($redisParamConverter->supports($this->configuration));
    }
}
