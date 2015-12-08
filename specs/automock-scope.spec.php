<?php

use Mrkrstphr\Peridot\Plugin\Automock\AutomockScope;
use Prophecy\Prophecy\ObjectProphecy;

describe(AutomockScope::class, function () {
    describe('->autoMock()', function () {
        it('should instantiate simple object with no constructor', function () {
            $scope = new AutomockScope();
            expect($scope->autoMock(AutomockScope::class))->to->be->instanceof(AutomockScope::class);
        });

        it('should mock all constructor dependencies', function () {
            $scope = new AutomockScope();
            $instance = $scope->autoMock(TestClass::class);

            expect($instance)->to->be->instanceof(TestClass::class);

            expect(substr(get_class($instance->foo), 0, 15))->to->equal('Double\\DateTime');
            expect($instance->foo)->to->be->instanceof(DateTime::class);

            expect(substr(get_class($instance->bar), 0, 19))->to->equal('Double\\DateTimeZone');
            expect($instance->bar)->to->be->instanceof(DateTimeZone::class);
        });

        it('should throw an exception if any constructor args are not objects', function () {
            $scope = new AutomockScope();

            $result = null;

            try {
                $scope->autoMock(TestClassNotAutoMockable::class);
            } catch (Exception $e) {
                $result = $e;
            }

            expect($result)->to->be->instanceof(RuntimeException::class);
        });
    });
});

// php7 save me...
class TestClass {
    public function __construct(DateTime $foo, DateTimeZone $bar) {
        $this->foo = $foo;
        $this->bar = $bar;
    }
}

class TestClassNotAutoMockable {
    public function __construct($foo) {}
}
