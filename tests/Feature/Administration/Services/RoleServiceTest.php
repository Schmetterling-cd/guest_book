<?php

namespace Administration\Services;

use App\Services\Administration\Role\RoleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class RoleServiceTest extends TestCase
{

    use RefreshDatabase;

    private const RESOURCES_KEYS_ROLE = ['roleId', 'roleName', 'permissions'];

    protected $seed = true;

    private function getTestData(): array {

        return [
            'roleId' => '2',
            'roleName' => 'TestRole name',
            'permissions' => [1,2],
        ];

    }

    public function setUp(): void
    {
        parent::setUp();

        $this->service = resolve(RoleService::class);
        $this->service->setData($this->getTestData());

    }

    public function testGetListRole()
    {

        $list = $this->service->getList()->toArray(new Request());

        foreach ($list as $item) {
            foreach (static::RESOURCES_KEYS_ROLE as $key) {
                $this->assertArrayHasKey($key, $item, 'Метод getList. Каждый элемент списка должен иметь свойство' . $key . '.');
            }
        }

    }

    public function testGetRole()
    {

        $role = $this->service->get()->toArray(new Request());

        $this->assertNotFalse($role, 'Метод getRole. Ошибка: ' . $this->service->getError());

        foreach (static::RESOURCES_KEYS_ROLE as $key) {
            $this->assertArrayHasKey($key, $role, 'Метод get. Каждый элемент списка должен иметь свойство' . $key . '.');
        }

    }

    public function testAddRole()
    {

        $roleId = $this->service->add();

        $this->assertNotFalse($roleId, 'Метод addRole. Ошибка: ' . $this->service->getError());

    }

    public function testUpdateRole()
    {

        $roleId = $this->service->update();

        $this->assertNotFalse($roleId, 'Метод update. Ошибка: ' . $this->service->getError());

    }

    public function testDeleteRole()
    {

        $this->assertNotTrue($this->service->delete(), 'Метод delete. Ошибка: ' . $this->service->getError());

    }

}
