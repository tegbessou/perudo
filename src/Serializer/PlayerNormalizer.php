<?php

namespace App\Serializer;

use App\Entity\Player;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;

class PlayerNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = 'PLAYER_ATTRIBUTE_NORMALIZER_ALREADY_CALLED';

    public function normalize($object, $format = null, array $context = [])
    {
        if ($object->isBot() && isset($context['groups']) && $key = array_search('player:read_if_not_bot', $context['groups'])) {
            unset($context['groups'][$key]);
        }

        $context[self::ALREADY_CALLED] = true;

        return $this->normalizer->normalize($object, $format, $context);
    }

    public function supportsNormalization($data, $format = null, array $context = [])
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof Player;
    }
}
