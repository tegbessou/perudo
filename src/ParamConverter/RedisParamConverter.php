<?php

namespace App\ParamConverter;

use App\Repository\AbstractRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RedisParamConverter implements ParamConverterInterface
{
    private iterable $redisStorageModels;
    private AbstractRepository $repository;

    public function __construct(iterable $redisStorageModels, AbstractRepository $repository)
    {
        $this->redisStorageModels = $redisStorageModels;
        $this->repository = $repository;
    }

    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $uuid = $this->getIdentifier($request, $configuration->getName());
        $this->checkExistence($uuid);

        $request->attributes->set($configuration->getName(), unserialize($this->repository->find($uuid)));

        return true;
    }

    public function supports(ParamConverter $configuration): bool
    {
        $redisStorageModelsTransform = $this->redisStorageModelsAsArray();

        if (!in_array($configuration->getClass(), $redisStorageModelsTransform)) {
            return false;
        }

        return true;
    }

    private function getIdentifier(Request $request, string $name): string
    {
        if ($request->attributes->has($name)) {
            return $request->attributes->get($name);
        }

        if ($request->attributes->has('uuid')) {
            return $request->attributes->get('uuid');
        }

        throw new \LogicException(sprintf('Unable to guess how to get a RedisStorageInterface instance from the request information for parameter "%s".', $name));
    }

    private function checkExistence(string $uuid): void
    {
        if (!$this->repository->exist($uuid)) {
            throw new NotFoundHttpException();
        }
    }

    private function redisStorageModelsAsArray(): array
    {
        $redisStorageModelsTransform = [];

        foreach ($this->redisStorageModels as $redisStorageModel) {
            $redisStorageModelsTransform[] = get_class($redisStorageModel);
        }

        return $redisStorageModelsTransform;
    }
}
