/* form-button */
/* contact和悬浮表单所有点击按钮设置 */
/* 添加圆角border-radius输入,默认为0px; */
.wpcf7-form .zendkee_form .frank-send .wpcf7-submit,.wpcf7-form .zendkee_form .form-line-country .dropdown>.wpcf7-dropdown-btn{
color: #fff;
background-image: none;
font-weight: 700 ;
padding: 10px 0;
text-align: center ;
height: 40px;
border: none !important;
line-height: 1 !important;
outline: none !important;
border-radius: <?php echo $form_button_radius_size; ?>px !important;
}

/* 表单提交按钮 */
.wpcf7-form .zendkee_form .frank-send .wpcf7-submit{
background-color: <?php echo $form_submit_button_bg_color; ?> !important;
}

/* 选择按钮 */
.wpcf7-form .zendkee_form .form-line-country .dropdown>.wpcf7-dropdown-btn{
background-color: <?php echo $form_select_button_bg_color; ?> !important;
}

.wpcf7-form .zendkee_form .frank-send .wpcf7-submit:hover{
background-color: <?php echo $form_submit_button_bg_color; ?> !important;
background-image: none !important;
}

.wpcf7-form .zendkee_form div .form-line .input-incorrect{
position: absolute;
right: -5px;
top: 20px;
background-color: #ea6060;
color: #fff;
text-align: center;
width: 20px;
height: 20px;
border-radius: 50%;
transform: translate(-50%,-50%) ;
}

.wpcf7-form .zendkee_form div .form-line .input-incorrect:before{
transform: translate(-50%, -50%) rotate(45deg);
}

.wpcf7-form .zendkee_form div .form-line .input-incorrect:after{
transform: translate(-50%, -50%) rotate(-45deg);
}

.wpcf7-form .zendkee_form div .form-line .input-incorrect:before,.wpcf7-form .zendkee_form div .form-line .input-incorrect:after{
content: "";
display: block;
width: 12px;
height: 2px;
background-color: #fff;
position: absolute;
top: 50%;
left: 50%;
z-index: 9999;
}

.wpcf7-form .zendkee_form div .form-line .input-prompt{
position: absolute;
right: -10px;
bottom: 120%;
background: #7f7f7f;
width: 85%;
padding: 10px;
color: #fff;
text-align: center;
border-radius: 10px;
z-index: 9999;
}

.wpcf7-form .zendkee_form div .form-line .input-prompt:before{
content: "";
border-top: solid 12px #7f7f7f;
border-left: solid 10px #00800000;
border-right: solid 10px #00800000;
border-bottom: solid 0px #00800000;
position: absolute;
top: 96%;
left: 70%;
z-index: 9999;
}

.wpcf7-form .zendkee_form div .form-line .input-tip {
position: absolute;
right: -5px;
top: 20px;
background-color: #e3b744;
color: #fff;
text-align: center;
width: 20px;
height: 20px;
border-radius: 50%;
transform: translate(-50%,-50%);
z-index: 9999;
}

.wpcf7-form .zendkee_form div .form-line .input-tip::before {
content: "!";
color: #fff;
font-weight: bold;
font-size: 14px;
line-height: 20px;
display: block;
}

/* zendkee_inquiry_float_form */
.side-form-wrapper #leo-side-contact-form{
padding: 20px 20px 0;
}

.wpcf7-form .zendkee_form.zendkee_inquiry_float_form{
padding: 0 !important;
}

.wpcf7-form .zendkee_form.zendkee_inquiry_float_form .form-title{
text-align: center;
margin: 0 0 5px;
color: #000;
padding: 0;
}

.wpcf7-form .zendkee_form.zendkee_inquiry_float_form .form-contain-all{
display: flex;
flex-wrap: wrap;
}

.wpcf7-form .zendkee_form.zendkee_inquiry_float_form div .form-line{
width: 100%;
padding: 0;
margin-bottom: 10px;
position: relative;
}

.wpcf7-form .zendkee_form.zendkee_inquiry_float_form div .form-line .wpcf7-form-control-wrap{
position: initial;
}

.wpcf7-form .zendkee_form.zendkee_inquiry_float_form div .form-line input{
padding: 0 10px;
height: 40px;
}

.wpcf7-form .zendkee_form div .form-line textarea{
padding: 10px;
}

.wpcf7-form .zendkee_form.zendkee_inquiry_float_form .form-line.form-line-country .dropdown{
display: flex;
flex-wrap: wrap;
width: 100%;
}

.wpcf7-form .zendkee_form.zendkee_inquiry_float_form .form-line.form-line-country .dropdown>.wpcf7-dropdown-value{
width: 100%;
margin: 0;
}

.wpcf7-form .zendkee_form.zendkee_inquiry_float_form .form-line.form-line-country .dropdown>.wpcf7-dropdown-btn{
width: 100%;
margin-top: 10px;
}

.wpcf7-form .zendkee_form.zendkee_inquiry_float_form .form-line.form-line-country .dropdown>.wpcf7-dropdown-content{
width: 100%;
margin: 0;
z-index: 6;
background-color: #fff;
}

.wpcf7-form .zendkee_form.zendkee_inquiry_float_form .form-line.frank-send .wpcf7-submit{
width: 100%;
}
/* end zendkee_inquiry_float_form */

/* zendkee_inquiry_form */
.wpcf7-form .zendkee_inquiry_form{
padding: 0;
}

.wpcf7-form .zendkee_inquiry_form p{
margin: 0;
}

.wpcf7-form .zendkee_inquiry_form .form-contain-half{
display: flex;
flex-wrap: wrap;
}

.wpcf7-form .zendkee_inquiry_form .form-contain-all{
display: flex;
flex-wrap: wrap;
}

.wpcf7-form .zendkee_inquiry_form div .form-line{
width: 100%;
margin-bottom: 10px;
position: relative;
}

.wpcf7-form .zendkee_inquiry_form div .form-line .wpcf7-form-control-wrap{
position: initial;
}

.wpcf7-form .zendkee_inquiry_form div .form-line input{
padding: 0 10px;
height: 40px;
}

.wpcf7-form .zendkee_inquiry_form .form-line.form-line-country .dropdown{
display: flex;
flex-wrap: wrap;
width: 100%;
}

.wpcf7-form .zendkee_inquiry_form .form-line.form-line-country .dropdown>.wpcf7-dropdown-value{
width: calc( 100% - 160px ) !important;
margin-right: 10px;
}

.wpcf7-form .zendkee_inquiry_form .form-line.form-line-country .dropdown>.wpcf7-dropdown-btn{
width: 150px !important;
}

.wpcf7-form .zendkee_inquiry_form .form-line.form-line-country .dropdown>.wpcf7-dropdown-content{
width: calc( 100% - 160px ) !important;
margin-right: 10px;
z-index: 6;
background-color: #fff;
}

.wpcf7-form .zendkee_inquiry_form .form-line.form-line-country .dropdown>.wpcf7-dropdown-content input{
outline: none;
border: none;
border-bottom: 1px solid #d2d2d2;
}

.wpcf7-form .zendkee_inquiry_form .form-line.frank-send .wpcf7-submit{
margin: 0 auto;
display: block;
width: 150px;
}

@media screen and (min-width: 801px){
.wpcf7-form .zendkee_inquiry_form .form-contain-half .form-line{
width: calc( 50% - 5px) ;
}

.wpcf7-form .zendkee_inquiry_form .form-contain-half .form-line:first-child{
margin-right: 5px;
}

.wpcf7-form .zendkee_inquiry_form .form-contain-half .form-line:last-child{
margin-left: 5px;
}
}

@media screen and (max-width: 800px){
.wpcf7-form .zendkee_inquiry_form .form-line.form-line-country .dropdown>.wpcf7-dropdown-value{
width: 100% !important;
margin-right: 0;
}

.wpcf7-form .zendkee_inquiry_form .form-line.form-line-country .dropdown>.wpcf7-dropdown-btn{
width: 100% !important;
margin-top: 10px;
}

.wpcf7-form .zendkee_inquiry_form .form-line.form-line-country .dropdown>.wpcf7-dropdown-content{
width: 100% !important;
margin-right: 0;
}

.wpcf7-form .zendkee_inquiry_form .form-line.frank-send .wpcf7-submit{
width: 100%;
}
}

/* end zendkee_inquiry_form */



/** wpcf7 dropdown start */

/* Dropdown Button */
.wpcf7-dropdown-btn {
background-color: #04AA6D;
color: white;
padding: 16px;
font-size: 16px;
border: none;
cursor: pointer;
}

/* Dropdown button on hover & focus */
.wpcf7-dropdown-btn:hover,
.wpcf7-dropdown-btn:focus {
background-color: #3e8e41;
}

/* The search field */
.wpcf7-dropdown-filter {
box-sizing: border-box;
background-image: url('searchicon.png');
background-position: 14px 12px;
background-repeat: no-repeat;
font-size: 16px;
padding: 14px 20px 12px 45px;
border: none;
border-bottom: 1px solid #ddd;
}

/* The search field when it gets focus/clicked on */
.wpcf7-dropdown-filter:focus {
outline: 3px solid #ddd;
}

/* The container <div> - needed to position the dropdown content */
  .dropdown {
  position: relative;
  display: inline-block;
  }

  /* Dropdown Content (Hidden by Default) */
  .wpcf7-dropdown-content {
  display: none;
  position: absolute;
  background-color: #f6f6f6;
  min-width: 230px;
  border: 1px solid #ddd;
  z-index: 1;
  height: 20em;
  overflow-y: scroll;
  overflow-x: hidden;
  }

  /* Links inside the dropdown */
  .wpcf7-dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
  }

  /* Change color of dropdown links on hover */
  .wpcf7-dropdown-content a:hover {
  background-color: #f1f1f1
  }

  /* Show the dropdown menu (use JS to add this class to the .wpcf7-dropdown-content container when the user clicks on the dropdown button) */
  .show {
  display: block;
  }
  
  /** wpcf7 dropdown end */