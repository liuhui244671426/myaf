放cache 和 model 文件
#多模块的模型文件
文件路径:模块名/模型名
例如:Admin/Index.php

类名:模块名_模型名Model
例如:Admin_IndexModel

继承:模型类继承BaseModel
例如:class Admin_IndexModel extends BaseModel


模型对资源的链接方式
调用工厂类HaloFactory::getFactory,
例如:$this->_db = \Our\Halo\HaloFactory::getFactory('db', 'user_center');