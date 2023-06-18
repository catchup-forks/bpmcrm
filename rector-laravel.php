<?php

declare(strict_types=1);

use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\StringType;
use Rector\CodeQuality\Rector\Catch_\ThrowWithPreviousExceptionRector;
use Rector\Config\RectorConfig;
use Rector\Core\Configuration\Option;
use Rector\DeadCode\Rector\ClassMethod\RemoveLastReturnRector;
use Rector\DeadCode\Rector\Node\RemoveNonExistingVarAnnotationRector;
use Rector\Php70\Rector\StaticCall\StaticCallOnNonStaticToInstanceCallRector;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\Php80\Rector\FuncCall\ClassOnObjectRector;
use Rector\Php81\Rector\Array_\FirstClassCallableRector;
use Rector\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector;
use Rector\Php82\Rector\Class_\ReadOnlyClassRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\Rector\Property\AddPropertyTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddPropertyTypeDeclaration;
use Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;
use RectorLaravel\Set\LaravelSetList;

// rector process app --config=rector-laravel.php

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/app',
        //__DIR__ . '/database',
    ]);

    $rectorConfig->skip([
        ClassOnObjectRector::class,
        StaticCallOnNonStaticToInstanceCallRector::class,
        FirstClassCallableRector::class,
        RemoveNonExistingVarAnnotationRector::class,
        RemoveLastReturnRector::class,

        __DIR__ . "/database/migrations/*",
        ReadOnlyClassRector::class => [
            __DIR__ . "/app/Events", // Because Laravel trait "InteractsWithSockets" have non read only property.
            __DIR__ . "/app/Jobs", // Because Laravel trait "InteractsWithQueue" have non read only property.
        ],
        SpatieEnumMethodCallToEnumConstRector::class,
    ]);

    $rectorConfig->importNames(true);
    $rectorConfig->importShortClasses();

    $rectorConfig->parameters()->set(Option::REMOVE_UNUSED_IMPORTS, true); // don't import everything, only affected

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
        LevelSetList::UP_TO_PHP_81,
        SetList::DEAD_CODE,
        SetList::PRIVATIZATION,
        SetList::EARLY_RETURN,
        SetList::TYPE_DECLARATION,
        LaravelSetList::LARAVEL_80,
        LaravelSetList::LARAVEL_CODE_QUALITY,
        //LaravelSetList::LARAVEL_LEGACY_FACTORIES_TO_CLASSES,
    ]);
};
