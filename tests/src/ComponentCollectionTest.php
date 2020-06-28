<?php

namespace Drieschel\UnitsOfMeasurement;

use PHPUnit\Framework\TestCase;

class ComponentCollectionTest extends TestCase
{
    /**
     * @dataProvider symbolsIndexedComponentsProvider
     *
     * @param array $indexedBySymbols
     * @param AbstractComponent ...$components
     * @throws CollectionException
     */
    public function testCount(array $indexedBySymbols, AbstractComponent ...$components)
    {
        $indexedBySymbolsCnt = count($indexedBySymbols);
        $collection = new ComponentCollection(...$components);
        $this->assertEquals($indexedBySymbolsCnt, $collection->count());
    }

    /**
     * @dataProvider symbolsIndexedComponentsProvider
     *
     * @param array $indexedBySymbols
     * @param AbstractComponent ...$components
     * @throws CollectionException
     */
    public function testCurrent(array $indexedBySymbols, AbstractComponent ...$components)
    {
        $collection = new ComponentCollection(...$components);
        foreach ($collection as $i => $component) {
            $this->assertEquals($indexedBySymbols[$i], $collection->current());
        }
        $this->assertNull($collection->current());
    }

    /**
     * @dataProvider componentsProvider
     *
     * @param AbstractComponent ...$components
     * @throws CollectionException
     */
    public function testHas(AbstractComponent ...$components)
    {
        $collection = new ComponentCollection(...$components);
        foreach ($components as $component) {
            $this->assertTrue($collection->has($component->getDefaultSymbol()));
            $this->assertFalse($collection->has($component->getName()));
        }
    }

    /**
     * @dataProvider symbolsIndexedComponentsProvider
     *
     * @param array $indexedBySymbols
     * @param AbstractComponent ...$components
     * @throws CollectionException
     */
    public function testMerge(array $indexedBySymbols, AbstractComponent ...$components)
    {
        $firstCollection = new ComponentCollection(...$this->componentsProvider('yalla', 1)[0]);
        $firstColCntBeforeMerge = $firstCollection->count();
        $secondCollection = new ComponentCollection(...$components);
        $firstCollection->merge($secondCollection);
        foreach ($components as $component) {
            $this->assertTrue($firstCollection->has($component->getDefaultSymbol()));
        }
        $this->assertEquals($secondCollection->count() + $firstColCntBeforeMerge, $firstCollection->count());
    }

    /**
     * @dataProvider componentsProvider
     *
     * @param AbstractComponent ...$components
     * @throws CollectionException
     */
    public function testSet(AbstractComponent ...$components)
    {
        $collection = new ComponentCollection();
        foreach ($components as $component) {
            $this->assertFalse($collection->has($component->getDefaultSymbol()));
            $collection->set($component);
            $this->assertTrue($collection->has($component->getDefaultSymbol()));
        }
    }

    public function testSetAbstractComponentWithMultipleSymbols()
    {
        $symbol1 = uniqid('s1-');
        $symbol2 = uniqid('s2-');
        $symbol3 = uniqid('s3-');
        $collection = new ComponentCollection();
        $component = $this->getMockForAbstractClass(AbstractComponent::class, ['symbol1', $symbol1])->addSymbols($symbol2, $symbol3);
        $collection->set($component);
        $this->assertEquals($component, $collection->get($symbol1));
        $this->assertEquals($component, $collection->get($symbol2));
        $this->assertEquals($component, $collection->get($symbol3));
    }

    public function testSetComponentInterface()
    {
        $symbol1 = uniqid('symbol1-');
        $symbol2 = uniqid('symbol2-');
        $collection = new ComponentCollection();
        $component = $this->createMock(ComponentInterface::class);
        $component->expects($this->any())->method('getAllSymbols')->willReturn([$symbol1, $symbol2]);
        $collection->set($component);
        $this->assertEquals($component, $collection->get($symbol1));
        $this->assertEquals($component, $collection->get($symbol2));
    }

    public function testSetSymbolExists()
    {
        $this->expectException(CollectionException::class);
        $this->expectExceptionCode(CollectionException::SYMBOL_EXISTS);
        $collection = new ComponentCollection();
        $symbol = uniqid('symbolix-');
        $component1 = $this->getMockForAbstractClass(AbstractComponent::class, ['name1', $symbol]);
        $component2 = $this->getMockForAbstractClass(AbstractComponent::class, ['name2', $symbol]);
        $collection->set($component1)->set($component2);
    }

    /**
     * @dataProvider symbolsIndexedComponentsProvider
     *
     * @param array $indexedBySymbols
     * @param AbstractComponent ...$components
     * @throws CollectionException
     */
    public function testValid(array $indexedBySymbols, AbstractComponent ...$components)
    {
        $indexedBySymbolsCnt = count($indexedBySymbols);
        $collection = new ComponentCollection(...$components);
        for ($i = 0; $i < $indexedBySymbolsCnt; $i++) {
            $this->assertTrue($collection->valid());
            $collection->next();
        }
        $this->assertFalse($collection->valid());
    }

    /**
     * @dataProvider componentsProvider
     *
     * @param AbstractComponent ...$components
     * @throws CollectionException
     */
    public function testFilterByClosure(AbstractComponent ...$components)
    {
        $max = mt_rand(1, 20);
        /** @var AbstractComponent[] $componentsToFilter */
        $componentsToFilter = [];
        for ($i = 0; $i < $max; $i++) {
            $componentsToFilter[] = $this->getMockForAbstractClass(AbstractComponent::class, [uniqid('name-'), uniqid('foobar-')]);
        }

        $allComponents = array_merge($componentsToFilter, $components);
        shuffle($allComponents);

        $collection = new ComponentCollection(...$allComponents);

        $closure = function (AbstractComponent $component) {
            return substr($component->getDefaultSymbol(), 0, 6) === 'foobar';
        };

        $filteredCollection = $collection->filterByClosure($closure);
        $this->assertCount(count($componentsToFilter), $filteredCollection);
        foreach ($componentsToFilter as $component) {
            $this->assertTrue($filteredCollection->has($component->getDefaultSymbol()));
        }
    }

    /**
     * @dataProvider symbolsIndexedComponentsProvider
     *
     * @param array $indexedBySymbols
     * @param AbstractComponent ...$components
     * @throws CollectionException
     */
    public function testRewind(array $indexedBySymbols, AbstractComponent ...$components)
    {
        $indexedBySymbolsCnt = count($indexedBySymbols);
        $collection = new ComponentCollection(...$components);
        $stepUntil = $indexedBySymbolsCnt > 0 ? mt_rand(0, $indexedBySymbolsCnt - 1) : 0;
        $firstSymbol = array_key_first($indexedBySymbols);
        for ($i = 0; $i < $stepUntil; $i++) {
            $collection->next();
            $this->assertNotEquals($firstSymbol, $collection->key());
        }
        $collection->rewind();
        $this->assertEquals($firstSymbol, $collection->key());
    }

    /**
     * @dataProvider componentsProvider
     *
     * @param AbstractComponent ...$components
     * @throws CollectionException
     */
    public function testGet(AbstractComponent ...$components)
    {
        $collection = new ComponentCollection(...$components);
        foreach ($components as $component) {
            foreach ($component->getAllSymbols() as $symbol) {
                $this->assertEquals($component, $collection->get($symbol));
            }
        }
    }

    public function testGetSymbolNotFound()
    {
        $this->expectException(CollectionException::class);
        $this->expectExceptionCode(CollectionException::SYMBOL_UNKNOWN);
        $collection = new ComponentCollection();
        $collection->get(uniqid('symboliax'));
    }

    /**
     * @dataProvider componentsProvider
     *
     * @param AbstractComponent ...$components
     * @throws CollectionException
     */
    public function testKey(AbstractComponent ...$components)
    {
        $collection = new ComponentCollection(...$components);
        foreach ($components as $component) {
            foreach ($component->getAllSymbols() as $symbol) {
                $this->assertEquals($symbol, $collection->key());
                $collection->next();
            }
        }
    }

    /**
     * @dataProvider symbolsIndexedComponentsProvider
     *
     * @param array $sortedBySymbols
     * @param AbstractComponent ...$components
     * @throws CollectionException
     */
    public function testNext(array $sortedBySymbols, AbstractComponent ...$components)
    {
        $collection = new ComponentCollection(...$components);
        foreach ($sortedBySymbols as $component) {
            $this->assertEquals($component, $collection->current());
            $collection->next();
        }
    }

    /**
     * @dataProvider componentsProvider
     *
     * @param AbstractComponent ...$components
     * @throws CollectionException
     */
    public function testOffsetExists(AbstractComponent ...$components)
    {
        $collection = new ComponentCollection(...$components);
        foreach ($components as $component) {
            $this->assertTrue($collection->offsetExists($component->getDefaultSymbol()));
            $this->assertFalse($collection->offsetExists($component->getName()));
        }
    }

    /**
     * @dataProvider componentsProvider
     *
     * @param AbstractComponent ...$components
     * @throws CollectionException
     */
    public function testOffsetGet(AbstractComponent ...$components)
    {
        $collection = new ComponentCollection(...$components);
        foreach ($components as $component) {
            foreach ($component->getAllSymbols() as $symbol) {
                $this->assertEquals($component, $collection->offsetGet($symbol));
            }
        }
    }

    /**
     * @dataProvider componentsProvider
     *
     * @param AbstractComponent[] $components
     * @return void
     * @throws CollectionException
     * @throws \ReflectionException
     */
    public function testOffsetSetDoesNotDoAnything(AbstractComponent ...$components)
    {
        $collection = new ComponentCollection(...$components);
        $reflClass = new \ReflectionClass($collection);
        $reflComponents = $reflClass->getProperty('components');
        $reflComponents->setAccessible(true);
        foreach ($components as $component) {
            foreach ($component->getAllSymbols() as $offset) {
                $collection->offsetSet($offset, 'wtf');
                $components = $reflComponents->getValue($collection);
                $this->assertEquals($components[$offset], $component);
            }
        }
    }

    /**
     * @dataProvider componentsProvider
     *
     * @param AbstractComponent ...$components
     * @throws CollectionException
     * @throws \ReflectionException
     */
    public function testOffsetUnsetDoesNotDoAnything(AbstractComponent ...$components): void
    {
        $collection = new ComponentCollection(...$components);
        $reflClass = new \ReflectionClass($collection);
        $reflComponents = $reflClass->getProperty('components');
        $reflComponents->setAccessible(true);
        foreach ($components as $component) {
            foreach ($component->getAllSymbols() as $offset) {
                $collection->offsetUnset($offset);
                $components = $reflComponents->getValue($collection);
                $this->assertEquals($components[$offset], $component);
            }
        }
    }

    /**
     * @dataProvider componentsProvider
     */
    public function testToArray(AbstractComponent ...$components)
    {
        $collection = new ComponentCollection(...$components);
        $this->assertEquals($components, $collection->toArray());
    }

    /**
     * @param string $testMethodName
     * @param int $rows
     * @return array
     */
    public function componentsProvider(string $testMethodName = 'yolo', $rows = 5): array
    {
        $minColumnElements = 1;
        if (in_array($testMethodName, ['testRewind', 'testFilterByClosure', 'testValid', 'testMerge', 'testCurrent', 'testCount', 'testToArray'], true)) {
            $minColumnElements = 0;
        }

        $data = [];
        for ($i = 0; $i < $rows; $i++) {
            $data[] = array_map(function ($null) {
                return $this->getMockForAbstractClass(AbstractComponent::class, [uniqid('name-'), uniqid('symbol-')])->addSymbols(uniqid('symbol-'));
            }, array_fill(0, mt_rand($minColumnElements, 20), null));
        }
        return $data;
    }

    /**
     * @param string $testMethodName
     * @return AbstractComponent[]
     */
    public function symbolsIndexedComponentsProvider(string $testMethodName = 'yolo'): array
    {
        $components = $this->componentsProvider($testMethodName);
        $rowCount = count($components);
        $resortedComponents = [];
        for ($i = 0; $i < $rowCount; $i++) {
            $resortedComponents[$i] = [[]];
            foreach ($components[$i] as $component) {
                foreach ($component->getAllSymbols() as $symbol) {
                    $resortedComponents[$i][0][$symbol] = $component;
                }
            }
            $resortedComponents[$i] = array_merge($resortedComponents[$i], $components[$i]);
        }
        return $resortedComponents;
    }

    /**
     * @return AbstractComponent[][]
     */
    public function twoComponentArraysProvider(): array
    {
        $max = mt_rand(1, 5);
        $data = [];
        for ($i = 0; $i < $max; $i++) {
            $data[] = [$this->componentsProvider(), $this->componentsProvider()];
        }
        return $data;
    }
}
