<?php

declare(strict_types=1);

use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\StringType;
use Rector\CodeQuality\Rector\Catch_\ThrowWithPreviousExceptionRector;
use Rector\Config\RectorConfig;
use Rector\Core\Configuration\Option;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\Rector\Property\AddPropertyTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddPropertyTypeDeclaration;
use Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;
use RectorLaravel\Set\LaravelSetList;

// rector process app --config=rector-laravel.php

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/database',
    ]);

    $rectorConfig->importNames(true);
    $rectorConfig->importShortClasses();

    $rectorConfig->parameters()->set(Option::REMOVE_UNUSED_IMPORTS, true); // don't import everything, only affected

    // register rules to add return type when known
    $rectorConfig->rules([
        ClassPropertyAssignToConstructorPromotionRector::class,
        ThrowWithPreviousExceptionRector::class,
    ]);

    $rectorConfig->ruleWithConfiguration(AddPropertyTypeDeclarationRector::class, [
        new AddPropertyTypeDeclaration('ParentClass', 'name', new StringType()),
    ]);
    $rectorConfig->ruleWithConfiguration(AddReturnTypeDeclarationRector::class, [
        new AddReturnTypeDeclaration('SomeClass', 'getData', new ArrayType(new MixedType(), new MixedType())),
    ]);

    // added this
    $rectorConfig->sets([
        LaravelSetList::LARAVEL_LEGACY_FACTORIES_TO_CLASSES,
    ]);
};
