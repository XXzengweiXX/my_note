#  安装

## 安装elasticsearch

1. docker pull elasticsearch:7.6.2
2. docker run -d -it -p 9200:9200 -p 9300:9300 --name es_7.6 -e "discovery.type=single-node" elasticsearch:7.6.2

> ps:-e "discovery.type=single-node"  必须加上,不然会报错

3. 访问127.0.0.1:9200,有内容则成功了

## 安装head插件

```sh
docker pull mobz/elasticsearch-head:5
docker run -d -it --name es_head -p 9100:9100 mobz/elasticsearch-head:5
```

### 解决es跨域

进入es容器,找到配置文件(config/elasticsearch.yml),添加

```yml
http.cors.enabled: true 
http.cors.allow-origin: "*"
```

重启es

```sh
docker restart es_7.6
```

### 解决es-head中创建索引报406的错误

1. 进入es-head,修改_site/vendor.js文件_
2. 如果vi不能用,分别执行apt-get update,apt-get install -y vim
3. 分别修改6888与7574行,把内容'application/x-www-form-urlencoded'改成'application/json;charset=UTF-8'
4. 重启es-head:docker-restart es-head

## 安装kibana

```sh
docker pull kibana:7.6.2
docker run -itd --name kibana -p 5601:5601 -e TZ='Asia/Shanghai' kibana:7.6.2
# 访问127.0.0.1:5601,若不能访问,提示不能连接es,进入容器,修改config/kibana.yml
elasticsearch.hosts: [ “http://192.168.56.10:9200” ]
# 然后重启kibana服务
# 汉化 修改config/kibana.yml
i18n.locale: "zh-CN"
```

## 分词器安装

```
下载分词插件:https://github.com/medcl/elasticsearch-analysis-ik/releases/tag/v7.6.2
加压并放到/plugins/ik里面
重启es
分词类型:
ik_smart:最少切分
ik_max_word:最细粒度切分
测试代码:

GET _analyze
{
    "analyzer":"ik_smart",
    "text":"中国共产党"
}
GET _analyze
{
    "analyzer":"ik_max_word",
    "text":"中国共产党"
}
```

在分词器中,可以在/plugins/ik/config/IKAnalyzer.cfg.xml文件中配置自己的字典

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE properties SYSTEM "http://java.sun.com/dtd/properties.dtd">
<properties>
	<comment>IK Analyzer 扩展配置</comment>
	<!--用户可以在这里配置自己的扩展字典 -->
	<entry key="ext_dict"></entry>
	 <!--用户可以在这里配置自己的扩展停止词字典-->
	<entry key="ext_stopwords"></entry>
	<!--用户可以在这里配置远程扩展字典 -->
	<!-- <entry key="remote_ext_dict">words_location</entry> -->
	<!--用户可以在这里配置远程扩展停止词字典-->
	<!-- <entry key="remote_ext_stopwords">words_location</entry> -->
</properties>

```

# 操作

## 索引(类似与mysql的database)

```js
# 创建一个索引并添加数据  PUT /索引名/类型/id  (类型在以后可能会被去掉)
PUT /zeng1/type1/1
{
    "name":"zeng",
    "age":12    
}
# 创建一个索引,并设置字段类型
PUT /test2
{
  "mappings": {
    "properties": {//设置字段类型
      "title":{
        "type": "text"//文本格式
      },
      "keyword":{
        "type": "keyword"//关键字,不可切割
      },
      "view_num":{
        "type": "integer"//数字
      },
      "created_at":{
        "type": "date"//日期
      }
    }
  }
}
# 数据查询
GET /zeng      //所有
GET /zeng1/_doc/1  //zeng1中_id为1的数据
# 服务默认配置查看
GET _cat/health
GET _cat/indices?v
# 更新 POST /索引/_doc/id/_update{"doc":{"key":"value"}}
POST /zeng1/_doc/1/_update
{
  "doc":{
    "age":56//只修改age
  }
}
# 删除
DELETE /test1   //删除索引及其所有的数据
DELETE /test1/_doc/1  //删除某一条数据
```

## 文档操作

```js
# 添加
PUT /test2/_doc/1
{
  "title":"早间要问",
  "keyword":"有时发生",
  "view_num":0,
  "created_at":"2019-12-26"
}
# 修改,末尾加上_update,不然没有注明的字段会被置空
POST /test2/_doc/3/_update
{
  "keyword":"中午吃什么呢",
  "created_at":"2019-12-20"
}
# 查询
GET /test2/_doc/1  // 根据_id
GET /test2/_doc/_search?q=title:早 //搜索title
GET /test2/_doc/_search
{
  "query":{
    "match":{
      "title":"早间"
    }
  },
  "_source":["title","keyword"],//指定返回字段
  "sort":[//根据某些字段来排序
    {
      "view_num":{
        "order":"desc"
      }
    }  
  ],
  # 分页    
  "from":1,//起始位置
  "size":2,//每页数据大小
  "highlight":{//高亮显示
      "fields":{
          "name":{},//设置name字段里高亮显示
      }
  }    
}
# 返回信息
{
  "took" : 1,
  "timed_out" : false,
  "_shards" : {
    "total" : 1,
    "successful" : 1,
    "skipped" : 0,
    "failed" : 0
  },
  "hits" : {
    "total" : {
      "value" : 3,
      "relation" : "eq"
    },
    "max_score" : 1.3093333,
    "hits" : [
      {
        "_index" : "test2",
        "_type" : "_doc",
        "_id" : "1",
        "_score" : 1.3093333,//权重
        "_source" : {
          "title" : "早间要问",
          "keyword" : "有时发生",
          "view_num" : 0,
          "created_at" : "2019-12-26"
        }
      },
      {
        "_index" : "test2",
        "_type" : "_doc",
        "_id" : "2",
        "_score" : 0.10536051,
        "_source" : {
          "title" : "晚间要问",
          "keyword" : "宵夜来啦",
          "view_num" : 5,
          "created_at" : "2019-12-28"
        }
      },
      {
        "_index" : "test2",
        "_type" : "_doc",
        "_id" : "3",
        "_score" : 0.10536051,
        "_source" : {
          "title" : "午间要问",
          "view_num" : 7
        }
      }
    ]
  }
}
```

