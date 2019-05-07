# Helloframework v1.0
### PHPmvc框架

```
一千个请求每次100并发输出hello word模板,windows\4核cpu
Percentage of the requests served within a certain time (ms)
    50%    317
    66%    332
    75%    348 
    80%    359
    90%    387
    95%    410
    98%    480
    99%    496
    100%    507 (longest request)
最大的响应时间小于 507 毫秒

Concurrency Level:      100
Time taken for tests:   3.275 seconds
Complete requests:      1000
Failed requests:        0
Requests per second:    305.36 [#/sec] (mean)
Time per request:       327.481 [ms] (mean)
Time per request:       3.275 [ms] (mean, across all concurrent requests)
Transfer rate:          527.52 [Kbytes/sec] received
```

路由null 是可以为空字段，可选，不填null 是必检查
