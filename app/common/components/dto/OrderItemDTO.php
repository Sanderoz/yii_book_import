<?php

namespace common\components\dto;

use common\components\exceptions\DTOException;
use ReflectionClass;
use Symfony\Contracts\Service\Attribute\Required;

class OrderItemDTO extends BaseDTO
{
    #[Required]
    public int $price;
    #[Required]
    public int $count;
    #[Required]
    public string $bookIsbn;
}