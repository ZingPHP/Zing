<?php /* Smarty version Smarty-3.1.15, created on 2014-02-07 22:42:05
         compiled from "C:\Users\rnaddy\Documents\NetBeansProjects\Zing\websites\zingTest\Templates\Home\main.tpl" */ ?>
<?php /*%%SmartyHeaderCode:752552f266645ce747-00065249%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ecf420610e2ac9f28979fd9a8633867e995b4153' => 
    array (
      0 => 'C:\\Users\\rnaddy\\Documents\\NetBeansProjects\\Zing\\websites\\zingTest\\Templates\\Home\\main.tpl',
      1 => 1391812897,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '752552f266645ce747-00065249',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.15',
  'unifunc' => 'content_52f266645d0596_64347901',
  'variables' => 
  array (
    'register' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52f266645d0596_64347901')) {function content_52f266645d0596_64347901($_smarty_tpl) {?><form action="/home/register" method="post">
    <p><?php echo $_smarty_tpl->tpl_vars['register']->value['email'];?>
</p>
    <p><?php echo $_smarty_tpl->tpl_vars['register']->value['username'];?>
</p>
    <p><?php echo $_smarty_tpl->tpl_vars['register']->value['password'];?>
</p>
    <p><?php echo $_smarty_tpl->tpl_vars['register']->value['phone'];?>
</p>
    <p><?php echo $_smarty_tpl->tpl_vars['register']->value['submit'];?>
</p>
</form><?php }} ?>
