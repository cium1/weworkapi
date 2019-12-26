### weworkapi
- 官方文档: [https://work.weixin.qq.com/api/doc/](https://work.weixin.qq.com/api/doc/)
- 原始项目: [https://github.com/sbzhu/weworkapi_php](https://github.com/sbzhu/weworkapi_php) 
- 安装方式:
```
composer require cium/weworkapi
```
- Director
```
├── api // API 接口
│   ├── struct // API接口需要使用到的一些数据结构
│   ├── README.md
│   └── API.php // API接口的关键逻辑
├── callback // 消息回调的一些方法
└── utils // 一些基础方法
```