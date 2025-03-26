/******* icon *******/
@font-face {
    font-family: "iconfont";
    src: url('./fonts/iconfont.eot?t=1576821873945');
    /* IE9 */
    src: url('./fonts/iconfont.eot?t=1576821873945#iefix') format('embedded-opentype'), /* IE6-IE8 */ url('data:application/x-font-woff2;charset=utf-8;base64,d09GMgABAAAAAAMoAAsAAAAABzwAAALbAAEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAHEIGVgCDHAqBfIFwATYCJAMQCwoABCAFhG0HRBteBsgusG3Yk8WEbEBx8TfsVwAM3BAP/+33v31mzrO5XzxJcqOJVrGqFslEEkRao3RW9/9dy/7sFEFiSrSsiimBWpRdykzT4UyKZCQpoDs2jtETub4jyN19WwAQFyccYkBllmDfDxgqy3qG1/ycT9w7rX1e5/PZt9zGWDzGtE9dgHFAgY45yQroC3LD2E0EDmE7gWZZG+Ha8GQIsiTQLxDvqByHrJxPSis5FPo1U1N8Uw3Ll3IP+Mp/H/95I4uiV4Ght+ZQCvD7d1zcyA2D0vgJ6uweKnaAJF7Vxp4QhcUTNWrSnAFDQ4Vf/7o6/j+lkx3L4F8eoahEHygcAyeart6YAhMEv64ICr/jBSr8kN5DNNwEHmrsxdpiIGYz8xI8Y/QJ8spkdL5bV3B0BPHzbdS6U4K/SliGXE0L6O3iEReOG4sH3MmXT8THHPFej0y6InfP8C/gM/6c4zuETdmLMt+NoPeiIXhcAA4bDkdwBKWHcTj8bA8zzxlO1/4QfMBLVtoCmjp++/Hjvfb2tvZ7nz6BCexU3znOob517EC94Lj176lx4x87RdffrC7Bz5pdi1tTP4MfqOit+NOKnnUZqoAj01djebRXWpEJCpqG/5tixoc5jPO9I4RhzizcuXEpQDW0Qk/2Dno6J+gbuohm28jszixGFDnCll0FYcp1FBN+oJpyn57s9+hZ8BV9UyGieRClS3bWguYMMBLnJIUSi4gmDV1CLp4jigNEnlXjLKvigghhNg0ij9NdTI4QnbA5RthzspdzCUnM0NCwshtRVQOZzJghSe6c5tzscLmkug85k4YGOa5iiDiOSEESFiFUkkEn0eZcTun7AYRsliqOtZAG0hEEY6PtIx5O7h70Eb3ei3Qvt9jmyLw4ToJIGIMGGVY6ESqVATHrO80gkjin6RERUwcXbCT11TvXN2pfuAUacFRGiRoZPf3nKcoNlqXH59pVavMMRqJUlwEAAA==') format('woff2'), url('./fonts/iconfont.woff?t=1576821873945') format('woff'), url('./fonts/iconfont.ttf?t=1576821873945') format('truetype'), /* chrome, firefox, opera, Safari, Android, iOS 4.2+ */ url('./fonts/iconfont.svg?t=1576821873945#iconfont') format('svg');
    /* iOS 4.1- */
}

.iconfont {
    font-family: "iconfont" !important;
    font-size: 16px;
    font-style: normal;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

.icon-editor:before {
    content: "\e6f6";
}

.icon-nav-list:before {
    content: "\e6fd";
}

.icon-rising:before {
    content: "\e761";
}

/******* icon *******/

.coly-menu-float,
.coly-form-float,
.coly-top-float {
    width: 40px;
    height: 40px;
}

.coly-form-float {
    background-color: #fff;
}

.coly-menu-float {
    background-color: <?php echo $float_button_bg_color;?>;
}

.coly-top-float {
    background-color: <?php echo $float_button_bg_color;?>;
    display: none;
}



.coly-side-right {
    /*width: 40px;*/
    padding: 2px 2px 0 2px;
    position: fixed;
    right: 20px;
    top: 63%;
    transition: all 0.5s;
    border-radius: 4px;
    z-index: 999999;
}

.coly-right {
    border-radius: 8px;
    margin-bottom: 26px;
}

.coly-right > a {
    width: 100%;
    height: 100%;
    text-align: center;
    display: block;
    line-height: 40px;
    color: #fff;
}

.iconfont {
    font-size: 30px;
    color:#fff

}


.quote-text .coly-menu-float{
    width: auto;
    padding: 0 6px;
}

/* 悬浮表单开始 */
/* leo float form*/
.side-form-wrapper {
    z-index: 9999999;
    position: fixed;
    right: 0;
}

/*  表单整体样式 */
#leo-side-contact-form {
    width: 300px;
    padding: 10px 20px 0;
    box-shadow: 0 0 20px #000;
    background: #fff;
    position: fixed;
    right: 10px;
    top: 50%;
    transform: translate(130%, -50%);
    transition: all 0.5s;
    z-index: 998;
}

#leo-side-contact-form.active {
    transform: translate(0, -50%);
}

/* 格式化 */
#leo-side-contact-form,
#leo-side-contact-form label,
#leo-side-contact-form input:not(.submit),
#leo-side-contact-form textarea,
#leo-side-contact-form h3 {
    font-family: inherit;
    box-sizing: border-box;
    color: #444f5d;
}

#leo-side-contact-form .form-title {
    font-size: 1.5em;
    font-weight: bold;
    text-align: center;
    padding-bottom: 10px;
}

#leo-side-contact-form .form-line label {
    font-weight: normal;
    text-align: left;
    font-size: 14px;
}

#leo-side-contact-form .form-line p {
    margin: 0;
}
div#coly-top-float {
    float: right;
}
#leo-side-contact-form.radius .form-line input {
    border-radius: 10px;
}
/* 格式化  结束 */

/* 表单关闭按钮 开始 */
#leo-side-contact-form .closeBtn {
    width: 30px;
    height: 30px;
    display: block;
    position: absolute;
    top: 5px;
    right: 5px;
    cursor: pointer;
}

#leo-side-contact-form .closeBtn:before,
#leo-side-contact-form .closeBtn:after {
    content: "";
    display: block;
    width: 20px;
    height: 2px;
    background-color: #000;
    position: absolute;
    top: 50%;
    left: 50%;
}

#leo-side-contact-form .closeBtn:before {
    transform: translate(-50%, -50%) rotate(45deg);
}

#leo-side-contact-form .closeBtn:after {
    transform: translate(-50%, -50%) rotate(-45deg);
}

/* 表单关闭按钮  结束 */

/* 涉及颜色部分  开始 */
/* title */
#leo-side-contact-form .form-line {
    width: 100%;
    padding-bottom: 5px;
}
#leo-side-contact-form .form-line.coly-bottom {
    padding-bottom: 10px;
}
/* textarea 和 非提交按钮 */
#leo-side-contact-form .form-line input,
#leo-side-contact-form .form-line textarea,
#leo-side-contact-form .form-line input:not(.submit):hover,
#leo-side-contact-form .form-line textarea:hover,
#leo-side-contact-form .form-line input:not(.submit):focus,
#leo-side-contact-form .form-line textarea:focus {
    display: block;
    width: 100%;
    height: auto;
    margin: auto;
    padding: 10px;
    background-color: #fff;
    border: 1px solid #ccc;
    outline: none;
    font-family: Hind, sans-serif;
    font-weight: 400;
    font-style: normal;
    line-height: 1.2em;
    text-decoration: none;
    text-transform: none;
    letter-spacing: 0px;
}
/* 提交按钮 */
#leo-side-contact-form .form-line input.submit,
#leo-side-contact-form .form-line input.submit:hover {
    width: 100%;
    border: 1px solid #ccc;
    background-color: #ccc;
    color: #fff;
}
/* 涉及颜色部分 结束 */

/* 悬浮表单结束 */

.show {
    display: block !important;
}

