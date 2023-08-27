# Payment Process Project

公司刚刚接入的新的WordPress系统，使用了woocommerce 这个插件来完成商店的制作。在设置支付方式时遇到了难题，我们签的支付接口商只有一个开放文档，没有任何现成的插件来直接启用。因为没有技术人员，去某宝某鱼找人做一个开价5000一个开价12000，百度接入三方支付一些接好的案例也是198、168，太气人了一点开源精神都没有。一气之下决定亲手操刀，只会一点点php 的我写出来了一些烂代码，又不是不能用。
这个项目实现了一个支付流程，具体步骤如下：

## pay.php

`pay.php` 初始化支付流程，注册相关函数并配置支付界面元素，如标题、接口logo等。用户点击付款后，`pay.php` 捕获并处理相关支付信息，包括支付金额、订单数据、用户IP、订单号等，然后将这些数据发送到 `redirect.php` 进一步处理。

## redirect.php

`redirect.php` 对收到的数据进行必要的处理和格式化，然后向 `process_payment.php` 发送处理后的数据以发起支付。

## process_payment.php

`process_payment.php` 接收到数据后，与支付接口商的服务器进行交互，发送支付请求。接口商处理请求后，返回一个支付URL。

`process_payment.php` 收到支付URL后，将用户重定向到该URL进行支付。用户在支付页面完成支付操作后，将被重定向到一个感谢页面。

## callback.php

接口商的服务器在监听到订单状态变为已付款后，会向 `callback.php` 发送一个回调请求，包含订单状态和其他相关信息。

`callback.php` 在接收到回调请求后，会根据接口商返回的数据检查付款状态。如果付款状态码为 '0000'，表示付款成功，`callback.php` 会提取出订单号，并将其POST到 `0000.php` 进行处理。

## 0000.php

`0000.php` 在接收到订单号后，会将对应woocommerce的订单标记为已付款。

希望这个项目能对你有所帮助！
