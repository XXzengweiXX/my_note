# orm

## with的实现方式

```php
Post::with('user')->get();
//对应sql如下：
select * from posts；
select * from users where users.id in (select cid from post)；
```

## with与load的区别

load只针对单个model对象，with可以是分页数据的预加载

## 策略

策略是在特定模型或者资源中组织授权逻辑的类。例如，你的应用是一个博客，那么你在创建或者更新博客的时候，你可能会有一个 `Post` 模型和一个对应的 `PostPolicy` 来授权用户动作。[go](https://learnku.com/docs/laravel/5.8/authorization/3908#f61c59)

