/*********************************************
 * Author       : Karthik Kaliyappan
* Created on    : May 15, 2014
* Last modified : Dec 12, 2014
* Description   : common jquery form validation
* Version       : 1.1
*********************************************/
var StoreFormValidation = {"forms":[]};
var FinalReturnValue    = true;
var FormDisplayedErr    = {"FormError":[]};
var FormNotification    = {"FormNotification":[]};
// Form field validation attributes 
var DefaultFormAttr = {
        CreateRules     : false, /*Page load validation*/
        ReValid         : false,
        ValidateForm    : 'form[validation="true"]', /* Default form Validation form selector*/
        FormAttr        : 'formvalidcode',/* do validation form attribute*/
        FieldAttr       : 'validcode', /* form field validation attribute*/
        FindRule        : 'jvalidation',/*  Form field validation rules attribute*/
        FieldConcatText : 'jvalid'
};
var DynamicFormAttr     = {
        FormSubmit      : false, /* Enable form submit */
        ShowErrMsg      : true, /*Enable/ disable display error message*/
        ShowErrBorder   : true, /*Enable/ disable error border*/
        ErrClass        : 'fieldError',/* fieldError - Display error field class name */
        FormSelector    : '',/*Validation form selector*/
        onTypeValid     : true
};
// Form field validation default error messages
var ErrorMessages       = {
        MsgFieldEmpty   : 'This field is required',
        MsgValidEmail   : 'Please enter a valid email address',
        MsgAlphaNum     : 'Alpha Numeric is required',
        MsgAlpha        : 'Please enter a alphabetical only',
        MsgNumber       : 'Please enter a numbers only',
        MsgMisMatch     : 'Password does not match',
        MsgSamePass     : 'Same password not allowed',
        MsgInvalidFile  : 'Invalid file format'
};
var RecreatedFieldString = function()
{
    var RecreatedStrObj     = this;
    // Function to recreate error string starts here
    RecreatedStrObj.RecreateErrorIdentify = function(AssignVal)
    {
        var MergedVal   = $.extend({RecreateString:''},DefaultFormAttr,AssignVal);
        if(MergedVal.RecreateString != '')
        {
            return MergedVal.RecreateString.replace(MergedVal.FieldConcatText,'');
        }
    };
};
// Generate Form validation rule methods starts here 
var FormValidationMethod = function()
{
    var MethodObj   = this;
    var FieldErrObj = new ErrFieldMethods();
    var RecreateStrObj      = new RecreatedFieldString();
    var FormErrorNotification = new FormNotificationStatus();
    // Validation form initialization
    MethodObj.FormInitialize = function(AssingVal)
    {
        var MergedVal   = $.extend({GetFormObj:''},DefaultFormAttr,AssingVal);
        if(MergedVal.GetFormObj!= '')
        {
            var CreateRandom        = "jformrule"+Math.floor(8796890 * Math.random());
            $(MergedVal.GetFormObj).attr(MergedVal.FormAttr,CreateRandom).attr({'onsubmit':'return false;'});
            return CreateRandom;
        }
    };
    // Check and reassign default message in JSON values list function starts here
    MethodObj.JsonValRulesChecking      = function(AssingCheckJsonVal)
        {
                var MergedCheckJsonVal  = $.extend({CheckingJsonVal:''},ErrorMessages,AssingCheckJsonVal);
                if(MergedCheckJsonVal.CheckingJsonVal != '')
                {
                        GetCheckRulesJsonVal    = MergedCheckJsonVal.CheckingJsonVal;
                        // Set rules to required field
                        if(GetCheckRulesJsonVal.Required == undefined)
                        {
                                GetCheckRulesJsonVal.Required   = '';
                                GetCheckRulesJsonVal.ShowErrMsg = '';
                        }
                        else
                        {
                                if(GetCheckRulesJsonVal.Required != '' && (GetCheckRulesJsonVal.ShowErrMsg == '' || GetCheckRulesJsonVal.ShowErrMsg == undefined))
                                {
                                        GetCheckRulesJsonVal.ShowErrMsg = MergedCheckJsonVal.MsgFieldEmpty;
                                }
                        }
                        // Set rules to minimum character length field
                        if(GetCheckRulesJsonVal.MinLength == undefined)
                        {
                                GetCheckRulesJsonVal.MinLength          = '';
                                GetCheckRulesJsonVal.MinLengthMsg       = '';
                        }
                        else
                        {
                                if(GetCheckRulesJsonVal.MinLength != '' && (GetCheckRulesJsonVal.MinLengthMsg == '' || GetCheckRulesJsonVal.MinLengthMsg == undefined))
                                {
                                        GetCheckRulesJsonVal.MinLengthMsg       = 'Minimum '+GetCheckRulesJsonVal.MinLength+' characters is required';
                                }
                        }
                        // Set rules to maximum character length field
                        if(GetCheckRulesJsonVal.MaxLength == undefined)
                        {
                                GetCheckRulesJsonVal.MaxLength          = '';
                                GetCheckRulesJsonVal.MaxLengthMsg       = '';
                        }
                        else
                        {
                                if(GetCheckRulesJsonVal.MaxLength != '' && (GetCheckRulesJsonVal.MaxLengthMsg == '' || GetCheckRulesJsonVal.MaxLengthMsg == undefined))
                                {
                                        GetCheckRulesJsonVal.MaxLengthMsg       = 'Cannot exceed '+GetCheckRulesJsonVal.MaxLength+' characters';
                                }
                        }
                        // Set rules to minimum checked check box length field
                        if(GetCheckRulesJsonVal.MinChkLength == undefined)
                        {
                                GetCheckRulesJsonVal.MinChkLength               = '';
                                GetCheckRulesJsonVal.MinChkLengthMsg    = '';
                        }
                        else
                        {
                                if(GetCheckRulesJsonVal.MinChkLength != '' && (GetCheckRulesJsonVal.MinChkLengthMsg == '' || GetCheckRulesJsonVal.MinChkLengthMsg == undefined))
                                {
                                        GetCheckRulesJsonVal.MinChkLengthMsg    = 'Minimum '+GetCheckRulesJsonVal.MinChkLength+' value is checked';
                                }
                        }
                        // Set rules to maximum checked check box length field
                        if(GetCheckRulesJsonVal.MaxChkLength == undefined)
                        {
                                GetCheckRulesJsonVal.MaxChkLength               = '';
                                GetCheckRulesJsonVal.MaxChkLengthMsg    = '';
                        }
                        else
                        {
                                if(GetCheckRulesJsonVal.MaxChkLength != '' && (GetCheckRulesJsonVal.MaxChkLengthMsg == '' || GetCheckRulesJsonVal.MaxChkLengthMsg == undefined))
                                {
                                        GetCheckRulesJsonVal.MaxChkLengthMsg    = 'Cannot exceed '+GetCheckRulesJsonVal.MaxChkLength+' value checked';
                                }
                        }
                        // Set rules to valid email validation field
                        if(GetCheckRulesJsonVal.IsMail == undefined)
                        {
                                GetCheckRulesJsonVal.IsMail                     = '';
                                GetCheckRulesJsonVal.IsMailErrMsg       = '';
                        }
                        else
                        {
                                if(GetCheckRulesJsonVal.IsMail != '' && (GetCheckRulesJsonVal.IsMailErrMsg == '' || GetCheckRulesJsonVal.IsMailErrMsg == undefined))
                                {
                                        GetCheckRulesJsonVal.IsMailErrMsg       = MergedCheckJsonVal.MsgValidEmail;
                                }
                        }
                        // Set rules to alpha numeric validation field
                        if(GetCheckRulesJsonVal.IsAlphaNum == undefined)
                        {
                                GetCheckRulesJsonVal.IsAlphaNum                 = '';
                                GetCheckRulesJsonVal.IsAlphaNumErrMsg   = '';
                        }
                        else
                        {
                                if(GetCheckRulesJsonVal.IsAlphaNum != '' && (GetCheckRulesJsonVal.IsAlphaNumErrMsg == '' || GetCheckRulesJsonVal.IsAlphaNumErrMsg == undefined))
                                {
                                        GetCheckRulesJsonVal.IsAlphaNumErrMsg   = MergedCheckJsonVal.MsgAlphaNum;
                                }
                        }
                        // Set rules to alpha(String) validation field
                        if(GetCheckRulesJsonVal.IsAlpha == undefined)
                        {
                                GetCheckRulesJsonVal.IsAlpha            = '';
                                GetCheckRulesJsonVal.IsAlphaErrMsg      = '';
                        }
                        else
                        {
                                if(GetCheckRulesJsonVal.IsAlpha != '' && (GetCheckRulesJsonVal.IsAlphaErrMsg == '' || GetCheckRulesJsonVal.IsAlphaErrMsg == undefined))
                                {
                                        GetCheckRulesJsonVal.IsAlphaErrMsg      = MergedCheckJsonVal.MsgAlpha;
                                }
                        }
                        if(GetCheckRulesJsonVal.NewErrMsg == undefined)
                        {
                                GetCheckRulesJsonVal.NewErrMsg          = '';
                        }
                        else
                        {
                                if(GetCheckRulesJsonVal.NewErrMsg != '')
                                {
                                        GetCheckRulesJsonVal.NewErrMsg  = GetCheckRulesJsonVal.NewErrMsg;
                                }
                        }
                        // Set rules to numeric validation field
                        if(GetCheckRulesJsonVal.IsNum == undefined)
                        {
                                GetCheckRulesJsonVal.IsNum              = '';
                                GetCheckRulesJsonVal.IsNumErrMsg        = '';
                        }
                        else
                        {
                                if(GetCheckRulesJsonVal.IsNum != '' && (GetCheckRulesJsonVal.IsNumErrMsg == '' || GetCheckRulesJsonVal.IsNumErrMsg == undefined))
                                {
                                        GetCheckRulesJsonVal.IsNumErrMsg        = MergedCheckJsonVal.MsgNumber;
                                }
                        }
                        // Set rules to password matching validation field
                        if(GetCheckRulesJsonVal.PassMatch == undefined)
                        {
                                GetCheckRulesJsonVal.PassMatch          = '';
                                GetCheckRulesJsonVal.PassErrMsg = '';
                        }
                        else
                        {
                                if(GetCheckRulesJsonVal.PassMatch != '' && (GetCheckRulesJsonVal.PassErrMsg == '' || GetCheckRulesJsonVal.PassErrMsg == undefined))
                                {
                                        GetCheckRulesJsonVal.PassErrMsg = MergedCheckJsonVal.MsgMisMatch;
                                }
                        }
                        // Set rules to password no matching validation field
                        if(GetCheckRulesJsonVal.NoMatch == undefined)
                        {
                                GetCheckRulesJsonVal.NoMatch            = '';
                                GetCheckRulesJsonVal.NoMatchErrMsg      = '';
                        }
                        else
                        {
                                if(GetCheckRulesJsonVal.NoMatch != '' && (GetCheckRulesJsonVal.NoMatchErrMsg == '' || GetCheckRulesJsonVal.NoMatchErrMsg == undefined))
                                {
                                        GetCheckRulesJsonVal.NoMatchErrMsg      = MergedCheckJsonVal.MsgSamePass;
                                }
                        }
                        // Set rules to accpet file format validation field
                        if(GetCheckRulesJsonVal.Accept == undefined)
                        {
                                GetCheckRulesJsonVal.Accept             = '';
                                GetCheckRulesJsonVal.FileErrMsg = '';
                        }
                        else
                        {
                                if(GetCheckRulesJsonVal.Accept != '' && (GetCheckRulesJsonVal.FileErrMsg == '' || GetCheckRulesJsonVal.FileErrMsg == undefined))
                                {
                                        GetCheckRulesJsonVal.FileErrMsg = MergedCheckJsonVal.MsgInvalidFile;
                                }
                        }
                        return GetCheckRulesJsonVal;
                }
        };
    // Converting string into JSON function starts here
        MethodObj.ConvertValidateRulesShowErrMsg = function(AssignedVal)
        {
                var MergedVal = $.extend({GetStringVal: ''},AssignedVal);
                if(MergedVal.GetStringVal != '')
                {
                        // Split values
                        var SplitJValidationVal = MergedVal.GetStringVal.split('|');
                        var ConvertToJsonVal    = {};
                        for(var StartLoop=0;StartLoop<SplitJValidationVal.length;StartLoop++)
                        {
                          InnerLoopVal  = SplitJValidationVal[StartLoop].split(':');
                          ConvertToJsonVal[InnerLoopVal[0]]= InnerLoopVal[1];
                        }
                        // ReAssign JSON validation rules
                        ConvertToJsonVal        = MethodObj.JsonValRulesChecking({CheckingJsonVal:ConvertToJsonVal});
                        return ConvertToJsonVal;
                }
        };// Converting string into JSON function ends here
        // Create form field validation rules - starts here
        MethodObj.CreateValidateRules = function(AssignedVal)
        {
                var MergedVal = $.extend({FormObj: '',FormIdentify:''},DefaultFormAttr,AssignedVal);
                var find_rule_field     = $('input['+MergedVal.FindRule+'],select['+MergedVal.FindRule+'],textarea['+MergedVal.FindRule+']',MergedVal.FormObj).length;
                if(find_rule_field > 0)
                {
                        $('input['+MergedVal.FindRule+'],select['+MergedVal.FindRule+'],textarea['+MergedVal.FindRule+']',MergedVal.FormObj).each(function()
                        {
                                var GetCurrentValidateObj               = $(this); 
                                // Get validation attr name
                                var GetjValidationValues                = GetCurrentValidateObj.attr(MergedVal.FindRule);
                                var GetCurrentValidateObjType   = GetCurrentValidateObj.attr('type');
                                var GetFormEleNodeName                  = GetCurrentValidateObj.context.nodeName.toLowerCase();
                                // Get assinged validation rules
                                var GetJsonRulesValue                   = MethodObj.ConvertValidateRulesShowErrMsg({GetStringVal:GetjValidationValues});
                                MethodObj.AssignFormValidationRules({JsonRuleVal:GetJsonRulesValue,FormFieldObj:GetCurrentValidateObj,UniqueForm:MergedVal.FormIdentify,NodeType:GetCurrentValidateObjType,NodeName:GetFormEleNodeName});
                        });
                }
                else
                {
                        MethodObj.AssignFormValidationRules({UniqueForm:MergedVal.FormIdentify});
                }
        };      // Create form field validation rules - ends here
        // Function to created random string starts here
        MethodObj.GenerateRandomString = function(AssignVal)
    {
        var MergedVal           = $.extend({StringLength:10},AssignVal);
                var ReturnString        = '';
                var DeclaredString = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                for(var StringLoop=0;StringLoop<MergedVal.StringLength;StringLoop++)
                {
                        ReturnString += DeclaredString.charAt(Math.floor(Math.random() * DeclaredString.length));
                }
                return ReturnString;
    };
        // Set JSON Form validation rules starts here
    MethodObj.AssignFormValidationRules = function(AssignedVal)
    {
        var MergedVal                   = $.extend({JsonRuleVal:'',FormFieldObj:'',UniqueForm:'',NodeType:'',NodeName:''},DefaultFormAttr,AssignedVal);
                if(MergedVal.UniqueForm != '')
                {
                        var CurrentFieldObj                     = MergedVal.FormFieldObj;
                        if(CurrentFieldObj == '')
                        {
                                FormValidateRule                        = {};
                                TextFieldValidateRules                  = {};
                                TextFieldValidateRules['FieldCode']     = '';
                                TextFieldValidateRules['NodeType']      = MergedVal.NodeType;
                                TextFieldValidateRules['NodeName']      = MergedVal.NodeName;
                                TextFieldValidateRules['rules']         = '';
                                FormValidateRule['formid']              = MergedVal.UniqueForm;
                                FormValidateRule['formvalidate']        = [];
                                FormValidateRule['formvalidate'].push(TextFieldValidateRules); 
                                StoreFormValidation['forms'].push(FormValidateRule);
                        }
                        else
                        {
                                var ConcateValdiateText         = MergedVal.FieldConcatText;
                                // Add additional identification for the error message
                                GetTxtBoxJsonRuleVal            = MergedVal.JsonRuleVal;
                                RandomSelectorName                      = MethodObj.GenerateRandomString()+ConcateValdiateText;
                                var CheckSameName                       = CurrentFieldObj.attr('name');
                                if(MergedVal.NodeName == 'input' && CheckSameName != '' && (MergedVal.NodeType == 'checkbox' || MergedVal.NodeType == 'radio'))
                                {
                                        var CheckSameNameObj    = $(MergedVal.NodeName+'[type="'+MergedVal.NodeType+'"][name="'+CheckSameName+'"]');
                                        var CheckSameNameLength = CheckSameNameObj.length;
                                        if(CheckSameNameLength > 1)
                                        {
                                                CheckSameNameObj.each(function()
                                                {
                                                        var SameNameObj = $(this);
                                                        var CheckFindRuleAttr   = SameNameObj.attr(MergedVal.FindRule);
                                                        if(CheckFindRuleAttr == '' || CheckFindRuleAttr == undefined)
                                                        {
                                                                SameNameObj.attr(MergedVal.FieldAttr,RandomSelectorName);
                                                        }
                                                });
                                        }
                                }
                                CurrentFieldObj.removeAttr(MergedVal.FindRule).attr(MergedVal.FieldAttr,RandomSelectorName);
                                FormFieldPresent        = true;
                                $.each(StoreFormValidation.forms,function(Key,Val)
                                {
                                        $.each(Val,function()
                                        {
                                                if(Val.formid == MergedVal.UniqueForm)
                                                {
                                                        FormFieldPresent        = false;
                                                }
                                        });
                                });
                                var GetErrorFormObj = 'form['+MergedVal.FormAttr+'='+MergedVal.UniqueForm+']';
                                if(FormFieldPresent)
                                {
                                         FormValidateRule                                               = {};
                                         TextFieldValidateRules                                 = {};
                                         TextFieldValidateRules['FieldCode']    = RandomSelectorName;
                                         TextFieldValidateRules['NodeType']             = MergedVal.NodeType;
                                         TextFieldValidateRules['NodeName']             = MergedVal.NodeName;
                                         TextFieldValidateRules['rules']                = GetTxtBoxJsonRuleVal;
                                         if(GetTxtBoxJsonRuleVal.NewErrMsg != '')
                                         {
                                                 var CreateNodeIdentify         = RecreateStrObj.RecreateErrorIdentify({RecreateString:RandomSelectorName});
                                                 FormErrorNotification.StoreFormNotification({FormObj:GetErrorFormObj,ShowBorder:MergedVal.ShowErrBorder,ShowMsg:MergedVal.ShowErrMsg});
                                                 FieldErrObj.DisplayErrMsgNode({ErrShowObj:CurrentFieldObj,ErrNodeIdentify:CreateNodeIdentify,DisplayErrMsg:GetTxtBoxJsonRuleVal.NewErrMsg,FormId:MergedVal.UniqueForm});
                                         }
                                         FormValidateRule['formid']                                     = MergedVal.UniqueForm;
                                         FormValidateRule['formvalidate']                       = [];
                                         FormValidateRule['formvalidate'].push(TextFieldValidateRules); 
                                         StoreFormValidation['forms'].push(FormValidateRule);
                                 }
                                 else
                                 {
                                         TextFieldValidateRules = {};
                                         TextFieldValidateRules['FieldCode']    = RandomSelectorName;
                                         TextFieldValidateRules['NodeType']                     = MergedVal.NodeType;
                                         TextFieldValidateRules['NodeName']                     = MergedVal.NodeName;
                                         TextFieldValidateRules['rules']                        = GetTxtBoxJsonRuleVal;
                                         if(GetTxtBoxJsonRuleVal.NewErrMsg != '')
                                         {
                                                 var CreateNodeIdentify         = RecreateStrObj.RecreateErrorIdentify({RecreateString:RandomSelectorName});
                                                 FormErrorNotification.StoreFormNotification({FormObj:GetErrorFormObj,ShowBorder:MergedVal.ShowErrBorder,ShowMsg:MergedVal.ShowErrMsg});
                                                 FieldErrObj.DisplayErrMsgNode({ErrShowObj:CurrentFieldObj,ErrNodeIdentify:CreateNodeIdentify,DisplayErrMsg:GetTxtBoxJsonRuleVal.NewErrMsg,FormId:MergedVal.UniqueForm});
                                         }
                                         $.each(StoreFormValidation.forms,function(CheckKey,CheckVal)
                                         {
                                                 $.each(CheckVal,function(InnerKey,InnerVal)
                                                 {
                                                         if(InnerKey == 'formid')
                                                         {
                                                                 if(CheckVal.formid == MergedVal.UniqueForm)
                                                                 {
                                                                         CheckVal['formvalidate'].push(TextFieldValidateRules);
                                                                 }
                                                         }
                                                 });
                                         });
                                 }
                        }
                }
    };// Set JSON Form validation rules starts here
    // Function to generate hidden value from form submit values
        MethodObj.GenerateHiddenValueFromForm   = function(AssignVal)
    {
        var MergedVal = $.extend({FormObj:''},AssignVal);
        if(MergedVal.FormObj != '')
        {
                $('input[type="submit"],button[type="submit"]',MergedVal.FormObj).each(function()
                {
                        if($('input[type="hidden"][name="'+$(this).attr('name')+'"]').length == 0)
                        {
                                $('<input type="hidden" />').appendTo(MergedVal.FormObj).attr('name',$(this).attr('name')).val($(this).val());
                        }
                });
        }
    };
};// Generate Form validation rule methods ends here
// Error message show/hide methods starts here
var ErrFieldMethods = function()
{
    var ErrFieldObj = this;
    var RecreateStrObj      = new RecreatedFieldString();
    var FormNotificationObj = new FormNotificationStatus();
    // Function to display invalid details 
    ErrFieldObj.InvalidDetailsPassed = function(AssignedRule)
    {
            if(FormValueInvalid == false)
            {
                    var MergedRule  = $.extend({FormObj:''},AssignedRule);
                    if(MergedRule.FormObj != '')
                    {
                            MergedRule.FormObj.attr('onsubmit','return false;');
                    }
                    return false;
            }
    };
     // Display message node starts here
    ErrFieldObj.DisplayErrMsgNode = function(AssingedVal)
    {
        var MergedVal   = $.extend({ErrShowObj:'',ErrNodeIdentify:'',DisplayErrMsg:'',FormId:''},DefaultFormAttr,AssingedVal);
        if(MergedVal.ErrShowObj != '' && MergedVal.ErrNodeIdentify != '' && MergedVal.DisplayErrMsg != '')
        {
            var ShowMsgStatus       = FormNotificationObj.ShowMsgStatus({FormId:MergedVal.FormId});
            var ShowBorderStatus    = FormNotificationObj.ShowBorderStatus({FormId:MergedVal.FormId});
            if(ShowMsgStatus == true)
            {
                var CheckSameFieldObj   = 'input['+MergedVal.FieldAttr+'="'+MergedVal.ErrNodeIdentify+MergedVal.FieldConcatText+'"]';
                var CheckSameLength                     = $(CheckSameFieldObj).length;
                if($('div.JValidationErr').hasClass(MergedVal.ErrNodeIdentify) == true)
                {
                    $('div.JValidationErr.'+MergedVal.ErrNodeIdentify).text(MergedVal.DisplayErrMsg);
                }
                else
                {
                    if(CheckSameLength > 1)
                    {
                            $(CheckSameFieldObj+':LAST').after("<div class='JValidationErr error "+MergedVal.ErrNodeIdentify+"'>"+MergedVal.DisplayErrMsg+"</div>");
                    }
                    else
                    {
                            MergedVal.ErrShowObj.after("<div class='JValidationErr error "+MergedVal.ErrNodeIdentify+"'>"+MergedVal.DisplayErrMsg+"</div>");
                    }
                }
            }
            if(ShowBorderStatus == true)
            {       
                    MergedVal.ErrShowObj.addClass(MergedVal.ErrClass); 
            }
        }
    };
    // Display message node ends here
    // Remove error message node
    ErrFieldObj.RemoveErrMsgNode = function(AssingedVal)
    {
        var MergedVal   = $.extend({RemoveErrNode:'',ErrRemoveObj:'',FormId:''},DynamicFormAttr,AssingedVal);
        if(MergedVal.RemoveErrNode != '')
        {
            var ShowMsgStatus       = FormNotificationObj.ShowMsgStatus({FormId:MergedVal.FormId});
            var ShowBorderStatus    = FormNotificationObj.ShowBorderStatus({FormId:MergedVal.FormId});
            if(ShowBorderStatus == true)
            {
                    MergedVal.ErrRemoveObj.removeClass(MergedVal.ErrClass);
            }
            if(ShowMsgStatus == true)
            {
                    $('div.JValidationErr.'+MergedVal.RemoveErrNode).remove();
            }
        }
    };
    // Function to set first field error focus starts here
    ErrFieldObj.ErrorFieldSetFocus      = function(AssignVal)
    {
        var MergedVal = $.extend({FormObj:''},DynamicFormAttr,AssignVal);
                if($('.'+MergedVal.ErrClass+':first:visible',MergedVal.FormObj).length > 0)
                {
                        var GetScrollHeight     = $('.'+MergedVal.ErrClass+':first:visible',MergedVal.FormObj)[0].scrollHeight;
                $('html,body').scrollTop(GetScrollHeight);
                $('.'+MergedVal.ErrClass+':first:visible',MergedVal.FormObj).focus();
                }
    };
    // Function to set first field error focus ends here
};
// Error message show/hide methods ends here
// Form field validation methods starts here
var FieldRestrictions   = function()
{
        var FieldMethodObj      = this;
        // Function to check valid mailid
        FieldMethodObj.ValidEmail = function(AssignVal)
    {
                var MergedVal = $.extend({CheckEmailVal:''},AssignVal);
                if(MergedVal.CheckEmailVal != '')
                {
                        var ValidateEmail = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                        if(ValidateEmail.test(MergedVal.CheckEmailVal) == true)
                        {
                                return true;
                        }
                        else
                        {
                                return false;
                        }
                }
    };
    // Function to check valid alpha numeric
    FieldMethodObj.IsAlphaNumeric = function(AssignVal)
    {
        var MergedVal = $.extend({CheckAlphaNumericVal:''},AssignVal);
                if(MergedVal.CheckAlphaNumericVal != '')
                {
                        var ValidateAlphaNumeric        = /^[A-Za-z0-9]+$/; 
                        // var ValidateAlphaNumeric     = /([a-zA-Z][0-9]+)$/;
                        if(ValidateAlphaNumeric.test(MergedVal.CheckAlphaNumericVal) == true)
                        {
                                return true;
                        }
                        else
                        {
                                return false;
                        }
                }        
    };
    // Function to validate valid string starts here
    FieldMethodObj.ValidString = function(AssignVal)
    {
                var MergedVal = $.extend({CheckStringVal:''},AssignVal);
                if(MergedVal.CheckStringVal != '')
                {
                        var ValidateString      = /^[a-zA-Z ]*$/;
                        if(ValidateString.test(MergedVal.CheckStringVal) == true)
                        {
                                return true;
                        }
                        else
                        {
                                return false;
                        }
                }  
    };
    // Function to validate valid numeric starts here
    FieldMethodObj.ValidNumber  = function(AssignVal)
    {
        var MergedVal = $.extend({CheckNumber:''},AssignVal);
                if(MergedVal.CheckNumber != '')
                {
                        var ValidateNumber      = /^[0-9]*$/;
                        if(ValidateNumber.test(MergedVal.CheckNumber) == true)
                        {
                                return true;
                        }
                        else
                        {
                                return false;
                        }
                }  
    };
    // Check match password function starts here
    FieldMethodObj.PasswordMatched      = function(AssignedRule)
        {
                var MergedRule                  = $.extend({CurrentPassVal:'',MatchPassField:'',FormObj:''},AssignedRule);
                if(MergedRule.CurrentPassVal != '' && MergedRule.MatchPassField != '' && MergedRule.FormObj != '')
                {
                        var MatchFieldObj       = $('input[type="password"][name="'+MergedRule.MatchPassField+'"]',MergedRule.FormObj);
                        var ReturnVal   = false;
                        if(MatchFieldObj.val() != '' && MatchFieldObj.val() == MergedRule.CurrentPassVal)
                        {
                                ReturnVal       = true;
                        }
                        return ReturnVal;
                }
        };
        // Check mis match password function starts here
    FieldMethodObj.PasswordNotMatched   = function(AssignedRule)
        {
                var MergedRule                  = $.extend({CurrentPassVal:'',MatchPassField:'',FormObj:''},AssignedRule);
                if(MergedRule.CurrentPassVal != '' && MergedRule.MatchPassField != '' && MergedRule.FormObj != '')
                {
                    var MatchFieldObj       = $('input[type="password"][name="'+MergedRule.MatchPassField+'"]',MergedRule.FormObj);
                    var ReturnVal   = false;
                    if(MergedRule.CurrentPassVal != '' && MatchFieldObj.val() != MergedRule.CurrentPassVal)
                    {
                            ReturnVal       = true;
                    }
                    return ReturnVal;
                }
        };
    // Check optional password field matched or not - created by Karthik K on 28 Oct, 2014
    FieldMethodObj.OptionalPasswordEmpty = function(AssignedRule)
    {
        var MergedRule                  = $.extend({CurrentPassVal:'',MatchPassField:'',FormObj:''},AssignedRule);
        var MatchFieldObj       = $('input[type="password"][name="'+MergedRule.MatchPassField+'"]',MergedRule.FormObj);
        if(MergedRule.CurrentPassVal != '' || MatchFieldObj.val() != '')
        {
                var ReturnVal   = false;
                if(MatchFieldObj.val() != MergedRule.CurrentPassVal)
                {
                        ReturnVal       = true;
                }
                return ReturnVal;
        }
    };
}; // Form field validation methods ends here
// Stored displayed error message form id method starts here
var StoreErrorForm      = function()
{
        var StoreErrFromObj             = this;
        StoreErrFromObj.StoreErrorFormDetails   = function(AssignedRule)
        {
                var MergedRule  = $.extend({FormId:''},AssignedRule);
                if(MergedRule.FormId != '')
                {
                        var FormErrorPresent    = false;
                        $.each(FormDisplayedErr.FormError,function(CheckKey,CheckVal)
                    {
                                 $.each(CheckVal,function(InnerKey,InnerVal)
                                 {
                                         if(CheckVal.FormId == MergedRule.FormId)
                                         {
                                                 FormErrorPresent       = true;
                                         }
                                 });
                        });
                        if(FormErrorPresent == false)
                        {
                                var ErrorForm   = {};
                                ErrorForm['FormId']     = MergedRule.FormId;
                                FormDisplayedErr['FormError'].push(ErrorForm);
                        }
                }
        };
        // Get error form status
        StoreErrFromObj.GetErrorFormStatus      = function(AssignedRule)
        {
                var MergedRule  = $.extend({FormId:''},AssignedRule);
                if(MergedRule.FormId != '')
                {
                    var FormErrorPresent    = false;
                    $.each(FormDisplayedErr.FormError,function(CheckKey,CheckVal)
                    {
                        $.each(CheckVal,function(InnerKey,InnerVal)
                        {
                            if(CheckVal.FormId == MergedRule.FormId)
                            {
                                FormErrorPresent       = true;
                            }
                        });
                    });
                    return FormErrorPresent;
                }
        };
}; // Stored displayed error message form id method ends here
// Store display error message and border status method starts here
var FormNotificationStatus = function()
{
    var NotificationObj = this;
    NotificationObj.StoreFormNotification   = function(AssignedRule)
    {
        var MergedRule  = $.extend({FormObj:'',ShowMsg:true,ShowBorder:true},DefaultFormAttr,AssignedRule);
        if(MergedRule.FormObj != '')
        {
            $(MergedRule.FormObj).each(function()
            {
                var FormErrorPresent    = false;
                var GetFormId           = $(this).attr(MergedRule.FormAttr);
                if(GetFormId != '' && GetFormId != undefined)
                {
                    $.each(FormNotification.FormNotification,function(CheckKey,CheckVal)
                    {
                        $.each(CheckVal,function(InnerKey,InnerVal)
                        {
                            if(CheckVal.FormId == GetFormId)
                            {
                                FormErrorPresent       = true;
                            }
                        });
                    });
                    if(FormErrorPresent == false)
                    {
                        var ErrorForm   = {};
                        ErrorForm['FormId']     = GetFormId;
                        ErrorForm['ShowMsg']    = MergedRule.ShowMsg;
                        ErrorForm['ShowBorder'] = MergedRule.ShowBorder;
                        FormNotification['FormNotification'].push(ErrorForm);
                    }
                }
            });
        }
    };
    // Get display form field error message
    NotificationObj.ShowMsgStatus      = function(AssignedRule)
    {
            var MergedRule  = $.extend({FormId:''},AssignedRule);
            if(MergedRule.FormId != '')
            {
                var ReturnStatus = false;
                $.each(FormNotification.FormNotification,function(CheckKey,CheckVal)
                {
                    $.each(CheckVal,function(InnerKey,InnerVal)
                    {
                        if(CheckVal.FormId == MergedRule.FormId && CheckVal.ShowMsg == true)
                        {
                            ReturnStatus       = true;
                        }
                    });
                });
                return ReturnStatus;
            }
    };
    // Get display form field error message
    NotificationObj.ShowBorderStatus      = function(AssignedRule)
    {
            var MergedRule  = $.extend({FormId:''},AssignedRule);
            if(MergedRule.FormId != '')
            {
                var ReturnStatus = false;
                $.each(FormNotification.FormNotification,function(CheckKey,CheckVal)
                {
                    $.each(CheckVal,function(InnerKey,InnerVal)
                    {
                        if(CheckVal.FormId == MergedRule.FormId && CheckVal.ShowBorder == true)
                        {
                            ReturnStatus       = true;
                        }
                    });
                });
                return ReturnStatus;
            }
    };
};
// Store display error message and border status method ends here
// Check for field validation method starts here
var CheckFieldValidation        = function()
{
        var CheckFieldObj       = this;
        var CheckFieldMethodObj = new FormValidationMethod();
        var CheckFieldErrObj    = new ErrFieldMethods();
        var CheckFieldRestrictObj = new FieldRestrictions();
        var ErrorFormObj        = new StoreErrorForm();
        var RecreateStrObj      = new RecreatedFieldString();
        CheckFieldObj.TextBoxFieldRules = function(AssignedRule) // Validate text for assigned rules function starts here
        {
                var MergedRule                  = $.extend({FormId:'',FormObj:'',FieldValidateName:'',FieldJsonRule:''},DynamicFormAttr,DefaultFormAttr,AssignedRule);
                var ValidateFormObj             = MergedRule.FormObj;
                var ValidateFieldObj    = $(ValidateFormObj+' input[type="text"]['+MergedRule.FieldAttr+'="'+MergedRule.FieldValidateName+'"]');
                var TextboxRule                 = MergedRule.FieldJsonRule;
                if(ValidateFieldObj.length > 0)
                {
                        var ValidateTxtVal              = $.trim(ValidateFieldObj.val());
                        var ValidateTxtLength           = ValidateTxtVal.length;
                        var CreateNodeIdentify          = RecreateStrObj.RecreateErrorIdentify({RecreateString:MergedRule.FieldValidateName});
                        if(TextboxRule.Required != '' && ValidateTxtVal == '' )
                        {
                                CheckFieldErrObj.DisplayErrMsgNode({ErrShowObj:ValidateFieldObj,ErrNodeIdentify:CreateNodeIdentify,DisplayErrMsg:TextboxRule.ShowErrMsg,FormId:MergedRule.FormId});
                                ValidateFieldObj.val('');
                                FinalReturnValue        = false;
                        }
                        else if(ValidateTxtVal != '' && TextboxRule.IsMail != '' && CheckFieldRestrictObj.ValidEmail({CheckEmailVal:ValidateTxtVal}) == false)
                        {
                                // Show field email error message
                                CheckFieldErrObj.DisplayErrMsgNode({ErrShowObj:ValidateFieldObj,ErrNodeIdentify:CreateNodeIdentify,DisplayErrMsg:TextboxRule.IsMailErrMsg,FormId:MergedRule.FormId});
                                FinalReturnValue        = false;
                        }
                        else if(ValidateTxtVal != '' && TextboxRule.IsAlpha != '' && CheckFieldRestrictObj.ValidString({CheckStringVal:ValidateTxtVal}) == false)
                        {
                                // Show field is alpha error message
                                CheckFieldErrObj.DisplayErrMsgNode({ErrShowObj:ValidateFieldObj,ErrNodeIdentify:CreateNodeIdentify,DisplayErrMsg:TextboxRule.IsAlphaErrMsg,FormId:MergedRule.FormId});
                                FinalReturnValue        = false;
                        }
                        else if(ValidateTxtVal != '' && TextboxRule.IsAlphaNum != '' && CheckFieldRestrictObj.IsAlphaNumeric({CheckAlphaNumericVal:ValidateTxtVal}) == false)
                        {
                                // Show field alpha numeric error message
                                CheckFieldErrObj.DisplayErrMsgNode({ErrShowObj:ValidateFieldObj,ErrNodeIdentify:CreateNodeIdentify,DisplayErrMsg:TextboxRule.IsAlphaNumErrMsg,FormId:MergedRule.FormId});
                                FinalReturnValue        = false;
                        }
                        else if(ValidateTxtVal != '' && TextboxRule.IsNum != '' && CheckFieldRestrictObj.ValidNumber({CheckNumber:ValidateTxtVal}) == false)
                        {
                                // Show field numeric error message
                                CheckFieldErrObj.DisplayErrMsgNode({ErrShowObj:ValidateFieldObj,ErrNodeIdentify:CreateNodeIdentify,DisplayErrMsg:TextboxRule.IsNumErrMsg,FormId:MergedRule.FormId});
                                FinalReturnValue        = false;
                        }
                        else if(ValidateTxtVal != '' && TextboxRule.MinLength != '' && TextboxRule.MinLength > ValidateTxtLength)
                        {
                                // Show field minimum length error message
                                CheckFieldErrObj.DisplayErrMsgNode({ErrShowObj:ValidateFieldObj,ErrNodeIdentify:CreateNodeIdentify,DisplayErrMsg:TextboxRule.MinLengthMsg,FormId:MergedRule.FormId});
                                FinalReturnValue        = false;
                        }
                        else if(ValidateTxtVal != '' && TextboxRule.MaxLength != '' && TextboxRule.MaxLength < ValidateTxtLength)
                        {
                                // Show field maximum length error message
                                CheckFieldErrObj.DisplayErrMsgNode({ErrShowObj:ValidateFieldObj,ErrNodeIdentify:CreateNodeIdentify,DisplayErrMsg:TextboxRule.MaxLengthMsg,FormId:MergedRule.FormId});
                                FinalReturnValue        = false;
                        }
                        else
                        {
                                // Remove error message
                                CheckFieldErrObj.RemoveErrMsgNode({RemoveErrNode:CreateNodeIdentify,ErrRemoveObj:ValidateFieldObj,FormId:MergedRule.FormId});
                        }
                }
                else
                {
                        FormValueInvalid        = false;
                        // Display invalid values passed        
                        CheckFieldErrObj.InvalidDetailsPassed();
                }
        }; // Validate text for assigned rules function ends here
        // Validate text for assigned rules function ends here
        CheckFieldObj.UploadFileFieldRules      = function(AssignedRule) // Validate upload file for assigned rules function starts here
        {
                var MergedRule                  = $.extend({FormId:'',FormObj:'',FieldValidateName:'',FieldJsonRule:''},DynamicFormAttr,DefaultFormAttr,AssignedRule);
                var ValidateFormObj             = MergedRule.FormObj;
                var ValidateFieldObj    = $(ValidateFormObj+' input[type="file"]['+MergedRule.FieldAttr+'="'+MergedRule.FieldValidateName+'"]');
                var UploadFileRule              = MergedRule.FieldJsonRule;
                if(ValidateFieldObj.length > 0)
                {
                        var ValidateFileVal                     = $.trim(ValidateFieldObj.val());
                        var CreateNodeIdentify          = RecreateStrObj.RecreateErrorIdentify({RecreateString:MergedRule.FieldValidateName});
                        if(UploadFileRule.Required != '' && ValidateFileVal == '')
                        {
                                CheckFieldErrObj.DisplayErrMsgNode({ErrShowObj:ValidateFieldObj,ErrNodeIdentify:CreateNodeIdentify,DisplayErrMsg:UploadFileRule.ShowErrMsg,FormId:MergedRule.FormId});
                                FinalReturnValue        = false;
                        }
                        else if(ValidateFileVal != '' && UploadFileRule.Accept != '')
                        {
                                var SplitAcceptType     = UploadFileRule.Accept.split(',');
                                var SliptFileName       = ValidateFileVal.split('.');
                                var GetFileTypeName     = SliptFileName.pop().toLowerCase();
                                var FileFormatError     = true;
                                for (var FileTypeCount = 0; FileTypeCount < SplitAcceptType.length; FileTypeCount++) 
                                {
                                        var GetAcceptFileType   = SplitAcceptType[FileTypeCount];
                                        if(GetFileTypeName == GetAcceptFileType)
                                        {
                                                FileFormatError = false;
                                                break;
                                        }
                                }
                                if(FileFormatError)
                                {
                                        CheckFieldErrObj.DisplayErrMsgNode({ErrShowObj:ValidateFieldObj,ErrNodeIdentify:CreateNodeIdentify,DisplayErrMsg:UploadFileRule.FileErrMsg,FormId:MergedRule.FormId});
                                        FinalReturnValue        = false;
                                }
                                else
                                {
                                        // Remove error message
                                        CheckFieldErrObj.RemoveErrMsgNode({RemoveErrNode:CreateNodeIdentify,ErrRemoveObj:ValidateFieldObj,FormId:MergedRule.FormId});
                                }
                        }
                        else
                        {
                                // Remove error message
                                CheckFieldErrObj.RemoveErrMsgNode({RemoveErrNode:CreateNodeIdentify,ErrRemoveObj:ValidateFieldObj,FormId:MergedRule.FormId});
                        }
                }
                else
                {
                        FormValueInvalid        = false;
                        // Display invalid values passed        
                        CheckFieldErrObj.InvalidDetailsPassed();
                }
        };// Validate upload file for assigned rules function ends here
        CheckFieldObj.PasswordFieldRules        = function(AssignedRule) // Validate password for assigned rules function starts here
        {
                var MergedRule                  = $.extend({FormId:'',FormObj:'',FieldValidateName:'',FieldJsonRule:''},DynamicFormAttr,DefaultFormAttr,AssignedRule);
                var ValidateFormObj             = MergedRule.FormObj;
                var ValidateFieldObj    = $(ValidateFormObj+' input[type="password"]['+MergedRule.FieldAttr+'="'+MergedRule.FieldValidateName+'"]');
                var PasswordRule                = MergedRule.FieldJsonRule;
                if(ValidateFieldObj.length > 0)
                {
                        var ValidatePassVal                     = $.trim(ValidateFieldObj.val());
                        var ValidateTxtLength           = ValidatePassVal.length;
                        var CreateNodeIdentify          = RecreateStrObj.RecreateErrorIdentify({RecreateString:MergedRule.FieldValidateName});;
                        if(PasswordRule.Required != '' && ValidatePassVal == '' || (PasswordRule.Holder != '' && PasswordRule.Holder == ValidatePassVal))
                        {
                                // Show field required error message
                                CheckFieldErrObj.DisplayErrMsgNode({ErrShowObj:ValidateFieldObj,ErrNodeIdentify:CreateNodeIdentify,DisplayErrMsg:PasswordRule.ShowErrMsg,FormId:MergedRule.FormId});
                                ValidateFieldObj.val('');
                                FinalReturnValue        = false;
                        }
                        else if(ValidatePassVal != '' && PasswordRule.MinLength != '' && PasswordRule.MinLength > ValidateTxtLength)
                        {
                                // Show field minimum length error message
                                CheckFieldErrObj.DisplayErrMsgNode({ErrShowObj:ValidateFieldObj,ErrNodeIdentify:CreateNodeIdentify,DisplayErrMsg:PasswordRule.MinLengthMsg,FormId:MergedRule.FormId});
                                FinalReturnValue        = false;
                        }
                        else if(ValidatePassVal != '' && PasswordRule.MaxLength != '' && PasswordRule.MaxLength < ValidateTxtLength)
                        {
                                // Show field maximum length error message
                                CheckFieldErrObj.DisplayErrMsgNode({ErrShowObj:ValidateFieldObj,ErrNodeIdentify:CreateNodeIdentify,DisplayErrMsg:PasswordRule.MaxLengthMsg,FormId:MergedRule.FormId});
                                FinalReturnValue        = false;
                        }
                        else if(ValidatePassVal != '' && PasswordRule.IsAlphaNum != '' && CheckFieldRestrictObj.IsAlphaNumeric({CheckAlphaNumericVal:ValidatePassVal}) == false)
                        {
                                // Show field alpha numeric error message
                                CheckFieldErrObj.DisplayErrMsgNode({ErrShowObj:ValidateFieldObj,ErrNodeIdentify:CreateNodeIdentify,DisplayErrMsg:PasswordRule.IsAlphaNumErrMsg,FormId:MergedRule.FormId});
                                FinalReturnValue        = false;
                        }       
                        else if(PasswordRule.NoMatch != '')
                        {
                            if(ValidatePassVal != '')
                            {
                                if(CheckFieldRestrictObj.PasswordMatched({CurrentPassVal:ValidatePassVal,MatchPassField:PasswordRule.NoMatch,FormObj:ValidateFormObj}) == true)
                                {
                                        // Show field password old and new matched error message
                                        CheckFieldErrObj.DisplayErrMsgNode({ErrShowObj:ValidateFieldObj,ErrNodeIdentify:CreateNodeIdentify,DisplayErrMsg:PasswordRule.NoMatchErrMsg,FormId:MergedRule.FormId});
                                        FinalReturnValue        = false;
                                }
                                else
                                {
                                        // Remove error message
                                        CheckFieldErrObj.RemoveErrMsgNode({RemoveErrNode:CreateNodeIdentify,ErrRemoveObj:ValidateFieldObj,FormId:MergedRule.FormId});
                                }
                            }
                            /*else if(ValidatePassVal == '' && PasswordRule.PassMatch != '')
                            {
                                if(CheckFieldRestrictObj.OptionalPasswordEmpty({CurrentPassVal:ValidatePassVal,MatchPassField:PasswordRule.PassMatch,FormObj:ValidateFormObj}) == true)
                                {
                                    // Show field password mismatched error message
                                    CheckFieldErrObj.DisplayErrMsgNode({ErrShowObj:ValidateFieldObj,ErrNodeIdentify:CreateNodeIdentify,DisplayErrMsg:PasswordRule.PassErrMsg,FormId:MergedRule.FormId});
                                    FinalReturnValue        = false;
                                }
                                else
                                {
                                    // Remove error message
                                    CheckFieldErrObj.RemoveErrMsgNode({RemoveErrNode:CreateNodeIdentify,ErrRemoveObj:ValidateFieldObj,FormId:MergedRule.FormId});
                                }
                            }*/
                        }
                        else if(PasswordRule.PassMatch != '')
                        {
                            if(ValidatePassVal != '')
                            {
                                if(CheckFieldRestrictObj.PasswordNotMatched({CurrentPassVal:ValidatePassVal,MatchPassField:PasswordRule.PassMatch,FormObj:ValidateFormObj}) == true)
                                {
                                        // Show field password mismatched error message
                                        CheckFieldErrObj.DisplayErrMsgNode({ErrShowObj:ValidateFieldObj,ErrNodeIdentify:CreateNodeIdentify,DisplayErrMsg:PasswordRule.PassErrMsg,FormId:MergedRule.FormId});
                                        FinalReturnValue        = false;
                                }
                                else
                                {
                                        // Remove error message
                                        CheckFieldErrObj.RemoveErrMsgNode({RemoveErrNode:CreateNodeIdentify,ErrRemoveObj:ValidateFieldObj,FormId:MergedRule.FormId});
                                }
                            }
                            else
                            {
                                if(CheckFieldRestrictObj.OptionalPasswordEmpty({CurrentPassVal:ValidatePassVal,MatchPassField:PasswordRule.PassMatch,FormObj:ValidateFormObj}) == true)
                                {
                                    // Show field password mismatched error message
                                    CheckFieldErrObj.DisplayErrMsgNode({ErrShowObj:ValidateFieldObj,ErrNodeIdentify:CreateNodeIdentify,DisplayErrMsg:PasswordRule.PassErrMsg,FormId:MergedRule.FormId});
                                    FinalReturnValue        = false;
                                }
                                else
                                {
                                    // Remove error message
                                    CheckFieldErrObj.RemoveErrMsgNode({RemoveErrNode:CreateNodeIdentify,ErrRemoveObj:ValidateFieldObj,FormId:MergedRule.FormId});
                                }
                            }
                        }
                        else
                        {
                                // Remove error message
                                CheckFieldErrObj.RemoveErrMsgNode({RemoveErrNode:CreateNodeIdentify,ErrRemoveObj:ValidateFieldObj,FormId:MergedRule.FormId});
                        }
                }
                else
                {
                        FormValueInvalid        = false;
                        // Display invalid values passed        
                        CheckFieldErrObj.InvalidDetailsPassed();
                }
        }; // Validate password for assigned rules function ends here
        CheckFieldObj.TextareaFieldRules        = function(AssignedRule) // Validate textarea field for assigned rules function starts here
        {
                var MergedRule                  = $.extend({FormId:'',FormObj:'',FieldValidateName:'',FieldJsonRule:''},DynamicFormAttr,DefaultFormAttr,AssignedRule);
                var ValidateFormObj             = MergedRule.FormObj;
                var ValidateFieldObj    = $(ValidateFormObj+' textarea['+MergedRule.FieldAttr+'="'+MergedRule.FieldValidateName+'"]');
                var TextareaRule                        = MergedRule.FieldJsonRule;
                if(ValidateFieldObj.length > 0)
                {
                        var ValidateTextareaVal         = ValidateFieldObj.val();
                        var ValidateTextareaLength      = ValidateTextareaVal.length;
                        var CreateNodeIdentify          = RecreateStrObj.RecreateErrorIdentify({RecreateString:MergedRule.FieldValidateName});
                        if(TextareaRule.Required != '' && ValidateTextareaVal == '' || (TextareaRule.Holder != '' && TextareaRule.Holder == ValidateTextareaVal))
                        {
                                // Show field required error message
                                CheckFieldErrObj.DisplayErrMsgNode({ErrShowObj:ValidateFieldObj,ErrNodeIdentify:CreateNodeIdentify,DisplayErrMsg:TextareaRule.ShowErrMsg,FormId:MergedRule.FormId});
                                ValidateFieldObj.val('');
                                FinalReturnValue        = false;
                        }
                        else if(TextareaRule.MinLength != '' && TextareaRule.MinLength > ValidateTextareaLength)
                        {
                                // Show field minimum length error message
                                CheckFieldErrObj.DisplayErrMsgNode({ErrShowObj:ValidateFieldObj,ErrNodeIdentify:CreateNodeIdentify,DisplayErrMsg:TextareaRule.MinLengthMsg,FormId:MergedRule.FormId});
                                FinalReturnValue        = false;
                        }
                        else if(TextareaRule.MaxLength != '' && TextareaRule.MaxLength < ValidateTextareaLength)
                        {
                                // Show field maximum length error message
                                CheckFieldErrObj.DisplayErrMsgNode({ErrShowObj:ValidateFieldObj,ErrNodeIdentify:CreateNodeIdentify,DisplayErrMsg:TextareaRule.MaxLengthMsg,FormId:MergedRule.FormId});
                                FinalReturnValue        = false;
                        }                                       
                        else
                        {
                                // Remove error message
                                CheckFieldErrObj.RemoveErrMsgNode({RemoveErrNode:CreateNodeIdentify,ErrRemoveObj:ValidateFieldObj,FormId:MergedRule.FormId});
                        }
                }
                else
                {
                        FormValueInvalid        = false;
                        // Display invalid values passed        
                        CheckFieldErrObj.InvalidDetailsPassed();
                }
        }; // Validate textarea for assigned rules function ends here
        CheckFieldObj.SelectBoxFieldRules       = function(AssignedRule) // Validate select box field for assigned rules function starts here
        {
                var MergedRule                  = $.extend({FormId:'',FormObj:'',FieldValidateName:'',FieldJsonRule:''},DynamicFormAttr,DefaultFormAttr,AssignedRule);
                var ValidateFormObj             = MergedRule.FormObj;
                var ValidateFieldObj    = $(ValidateFormObj+' select['+MergedRule.FieldAttr+'="'+MergedRule.FieldValidateName+'"]');
                var SelectboxRule               = MergedRule.FieldJsonRule;
                if(ValidateFieldObj.length > 0)
                {
                        var ValidateFieldVal    = ValidateFieldObj.val();
                        var CreateNodeIdentify  = RecreateStrObj.RecreateErrorIdentify({RecreateString:MergedRule.FieldValidateName});
                        if(SelectboxRule.Required != '' && (ValidateFieldVal == '' || (SelectboxRule.Default != '' && SelectboxRule.Default == ValidateFieldVal)))
                        {
                                // Show field required error message
                                CheckFieldErrObj.DisplayErrMsgNode({ErrShowObj:ValidateFieldObj,ErrNodeIdentify:CreateNodeIdentify,DisplayErrMsg:SelectboxRule.ShowErrMsg,FormId:MergedRule.FormId});
                                FinalReturnValue        = false;
                        }
                        else
                        {
                                // Remove error message
                                CheckFieldErrObj.RemoveErrMsgNode({RemoveErrNode:CreateNodeIdentify,ErrRemoveObj:ValidateFieldObj,FormId:MergedRule.FormId});
                        }
                }
                else
                {
                        FormValueInvalid        = false;
                        // Display invalid values passed        
                        CheckFieldErrObj.InvalidDetailsPassed();
                }
        }; // Validate select box field assigned rules function ends here
        CheckFieldObj.CheckBoxFieldRules        = function(AssignedRule) // Validate check box field for assigned rules function starts here
        {
                var MergedRule                  = $.extend({FormId:'',FormObj:'',FieldValidateName:'',FieldJsonRule:''},DynamicFormAttr,DefaultFormAttr,AssignedRule);
                var ValidateFormObj             = MergedRule.FormObj;
                var ValidateFieldObj    = $(ValidateFormObj+' input[type="checkbox"]['+MergedRule.FieldAttr+'="'+MergedRule.FieldValidateName+'"]');
                var CheckBoxRule                = MergedRule.FieldJsonRule;
                var GetChkAttrName              = ValidateFieldObj.attr('name');
                var GetChkCheckedLength = $(ValidateFormObj+' input[type="checkbox"][name="'+GetChkAttrName+'"]:checked').length;
                if(ValidateFieldObj.length > 0)
                {
                        var CreateNodeIdentify  = RecreateStrObj.RecreateErrorIdentify({RecreateString:MergedRule.FieldValidateName});;
                        if(CheckBoxRule.Required != '' && GetChkCheckedLength == 0)
                        {
                                // Show field required error message
                                CheckFieldErrObj.DisplayErrMsgNode({ErrShowObj:ValidateFieldObj,ErrNodeIdentify:CreateNodeIdentify,DisplayErrMsg:CheckBoxRule.ShowErrMsg,FormId:MergedRule.FormId});
                                FinalReturnValue        = false;
                        }
                        else if(CheckBoxRule.MinChkLength != '' && CheckBoxRule.MinChkLength > GetChkCheckedLength)
                        {
                                // Show minimum checked error message
                                CheckFieldErrObj.DisplayErrMsgNode({ErrShowObj:ValidateFieldObj,ErrNodeIdentify:CreateNodeIdentify,DisplayErrMsg:CheckBoxRule.MinChkLengthMsg,FormId:MergedRule.FormId});
                                FinalReturnValue        = false;
                        }
                        else if(CheckBoxRule.MaxChkLength != '' && CheckBoxRule.MaxChkLength < GetChkCheckedLength)
                        {
                                // Show maximum checked error message
                                CheckFieldErrObj.DisplayErrMsgNode({ErrShowObj:ValidateFieldObj,ErrNodeIdentify:CreateNodeIdentify,DisplayErrMsg:CheckBoxRule.MaxChkLengthMsg,FormId:MergedRule.FormId});
                                FinalReturnValue        = false;
                        }
                        else
                        {
                                // Remove error message
                                CheckFieldErrObj.RemoveErrMsgNode({RemoveErrNode:CreateNodeIdentify,ErrRemoveObj:ValidateFieldObj,FormId:MergedRule.FormId});
                        }
                }
                else
                {
                        FormValueInvalid        = false;
                        // Display invalid values passed        
                        CheckFieldErrObj.InvalidDetailsPassed();
                }
        }; // Validate checkbox field for assigned rules function ends here
        CheckFieldObj.RadioButtonFieldRules     = function(AssignedRule) // Validate radio button field function starts here
        {
                var MergedRule                  = $.extend({FormId:'',FormObj:'',FieldValidateName:'',FieldJsonRule:''},DynamicFormAttr,DefaultFormAttr,AssignedRule);
                var ValidateFormObj             = MergedRule.FormObj;
                var ValidateFieldObj    = $(ValidateFormObj+' input[type="radio"]['+MergedRule.FieldAttr+'="'+MergedRule.FieldValidateName+'"]');
                var RadioButtonRule             = MergedRule.FieldJsonRule;
                var GetRadioAttrName            = ValidateFieldObj.attr('name');
                var GetRadioCheckedLength       = $(ValidateFormObj+' input[type="radio"][name="'+GetRadioAttrName+'"]:checked').length;
                if(ValidateFieldObj.length > 0)
                {
                        var CreateNodeIdentify  = RecreateStrObj.RecreateErrorIdentify({RecreateString:MergedRule.FieldValidateName});;
                        if(RadioButtonRule.Required != '' && GetRadioCheckedLength == 0)
                        {
                                // Show field required error message
                                CheckFieldErrObj.DisplayErrMsgNode({FormId:MergedRule.FormId,ErrShowObj:ValidateFieldObj,ErrNodeIdentify:CreateNodeIdentify,DisplayErrMsg:RadioButtonRule.ShowErrMsg});
                                FinalReturnValue        = false;
                        }
                        else
                        {
                                // Remove error message
                                CheckFieldErrObj.RemoveErrMsgNode({RemoveErrNode:CreateNodeIdentify,ErrRemoveObj:ValidateFieldObj,FormId:MergedRule.FormId});
                        }
                }
                else
                {
                        FormValueInvalid        = false;
                        // Display invalid values passed        
                        CheckFieldErrObj.InvalidDetailsPassed();
                }
        }; // Validate radio button field function ends here
        // Check form field validation rules
        CheckFieldObj.CheckFormFieldRules       = function(Options)
        {
                var Defaults    = $.extend({JsonCheckRule:'',FormId:''},DefaultFormAttr,DynamicFormAttr,Options);
                if(Defaults.JsonCheckRule != '' && Defaults.FormId != '')
                {
                        $.each(Defaults.JsonCheckRule,function(FieldKey,FieldVal)
                        {
                                var GetFieldValidateName        = FieldVal.FieldCode;
                                var GetNodeName                         = FieldVal.NodeName;
                                var GetNodeType                         = FieldVal.NodeType;
                                var ValidateFieldRules          = FieldVal.rules;
                                var ValidateFormObj                     = 'form['+Defaults.FormAttr+'="'+Defaults.FormId+'"]';
                                ErrorFormObj.StoreErrorFormDetails({FormId:Defaults.FormId});
                                if(GetNodeName == 'input')
                                {
                                        if(GetNodeType == 'text')
                                        {
                                                CheckFieldObj.TextBoxFieldRules({FormId:Defaults.FormId,FormObj:ValidateFormObj,FieldValidateName:GetFieldValidateName,FieldJsonRule:ValidateFieldRules});
                                        }
                                        if(GetNodeType == 'file')
                                        {
                                                CheckFieldObj.UploadFileFieldRules({FormId:Defaults.FormId,FormObj:ValidateFormObj,FieldValidateName:GetFieldValidateName,FieldJsonRule:ValidateFieldRules});
                                        }
                                        if(GetNodeType == 'checkbox')
                                        {
                                                CheckFieldObj.CheckBoxFieldRules({FormId:Defaults.FormId,FormObj:ValidateFormObj,FieldValidateName:GetFieldValidateName,FieldJsonRule:ValidateFieldRules});
                                        }
                                        if(GetNodeType == 'radio')
                                        {
                                                CheckFieldObj.RadioButtonFieldRules({FormId:Defaults.FormId,FormObj:ValidateFormObj,FieldValidateName:GetFieldValidateName,FieldJsonRule:ValidateFieldRules});
                                        }
                                        if(GetNodeType == 'password')
                                        {
                                                CheckFieldObj.PasswordFieldRules({FormId:Defaults.FormId,FormObj:ValidateFormObj,FieldValidateName:GetFieldValidateName,FieldJsonRule:ValidateFieldRules});
                                        }
                                }
                                if(GetNodeName == 'select')
                                {
                                        CheckFieldObj.SelectBoxFieldRules({FormId:Defaults.FormId,FormObj:ValidateFormObj,FieldValidateName:GetFieldValidateName,FieldJsonRule:ValidateFieldRules});
                                }
                                if(GetNodeName == 'textarea')
                                {
                                        CheckFieldObj.TextareaFieldRules({FormId:Defaults.FormId,FormObj:ValidateFormObj,FieldValidateName:GetFieldValidateName,FieldJsonRule:ValidateFieldRules});
                                }
                        });
                }
        };
}; // Check for field validation method ends here
// Keyboard events method starts here
 var FieldKeyBoardEvents = function()
 {
         var KeyboardEvntObj            = this;
         var FieldValidationMethod      = new CheckFieldValidation();
         var ErrorFormStatusObj         = new StoreErrorForm();
         KeyboardEvntObj.DoValidationDynamic = function(options)
         {
                 var Defaults   = $.extend({FormSelector:''},DefaultFormAttr,DynamicFormAttr,options);
                 var CurrentFormObj             = $(Defaults.FormSelector);
                 var GetFormId                  = CurrentFormObj.attr(Defaults.FormAttr);
                 if(GetFormId != '' && GetFormId != undefined)
                 {
                        $.each(StoreFormValidation.forms,function(ValidateKey,ValidateVal)
                        {
                                if(ValidateVal.formid == GetFormId)
                                {
                                        $('input['+Defaults.FieldAttr+'][type="text"],input['+Defaults.FieldAttr+'][type="password"],textarea['+Defaults.FieldAttr+']',CurrentFormObj).bind('keyup blur',function(PreEvent)
                                        {
                                                PreEvent.preventDefault();
                                                // Get error formstatus
                                                var GetErroFormStatus = ErrorFormStatusObj.GetErrorFormStatus({FormId:GetFormId});
                                                if(GetErroFormStatus == true)
                                        {
                                                        FieldValidationMethod.CheckFormFieldRules({JsonCheckRule:ValidateVal.formvalidate,FormId:GetFormId});
                                        }
                                        });
                                        $('input['+Defaults.FieldAttr+'][type="checkbox"],input['+Defaults.FieldAttr+'][type="radio"],input['+Defaults.FieldAttr+'][type="file"],select['+Defaults.FieldAttr+']',CurrentFormObj).bind('change',function(PreEvent)
                                        {
                                                PreEvent.preventDefault();
                                                // Get error formstatus
                                                var GetErroFormStatus = ErrorFormStatusObj.GetErrorFormStatus({FormId:GetFormId});
                                                if(GetErroFormStatus == true)
                                        {
                                                        FieldValidationMethod.CheckFormFieldRules({JsonCheckRule:ValidateVal.formvalidate,FormId:GetFormId});
                                        }
                                        });
                                }
                        });
                 }
         };
 };
// Keyboard events method ends here
//Form validation functions methods starts here
$.FormRule = function(options)
{
        var ValidationMethods           = new FormValidationMethod();
        var FieldValidationMethod       = new CheckFieldValidation();
        var CheckFormFieldErrObj        = new ErrFieldMethods();
        var FieldDynamicValidation      = new FieldKeyBoardEvents();
        var FormNotificationObj         = new FormNotificationStatus();
        var Extended                    = $.extend(DefaultFormAttr,DynamicFormAttr,ErrorMessages,options);
        if(Extended.FormSubmit == true && Extended.FormSelector != '' && Extended.ReValid == false) // Do form submit validation loop starts here
        {          
                FormNotificationObj.StoreFormNotification({FormObj:Extended.FormSelector,ShowBorder:Extended.ShowErrBorder,ShowMsg:Extended.ShowErrMsg});
                $(Extended.FormSelector).submit(function(FmEvent)
                {
                        FmEvent.preventDefault();
                        var CurrentFormObj              = $(this);
                        var GetFormId                   = CurrentFormObj.attr(Extended.FormAttr);
                        FormValueInvalid                = false;
                        if(GetFormId != '' && GetFormId != undefined)
                        {
                            $.each(StoreFormValidation.forms,function(ValidateKey,ValidateVal)
                            {
                                if(ValidateVal.formid == GetFormId)
                                {
                                    if(Extended.onTypeValid == true)
                                    {
                                            // Form field dynamic valdiation
                                            FieldDynamicValidation.DoValidationDynamic({FormSelector:Extended.FormSelector});
                                    }
                                    FormValueInvalid        = true;
                                    FinalReturnValue        = true;
                                    FieldValidationMethod.CheckFormFieldRules({JsonCheckRule:ValidateVal.formvalidate,FormId:GetFormId});
                                    if(FinalReturnValue == true && FormValueInvalid == true)
                                    {
                                            ValidationMethods.GenerateHiddenValueFromForm({FormObj:CurrentFormObj});
                                            CurrentFormObj.removeAttr('onsubmit');
                                            CurrentFormObj[0].submit();
                                    }
                                    else
                                    {
                                            CheckFormFieldErrObj.ErrorFieldSetFocus({FormObj:CurrentFormObj,ErrClass:Extended.ErrClass});
                                            return false;
                                    }
                                }
                            });
                        }
                });
        }// Do form submit validation loop ends here
        else if(Extended.FormSubmit == false && Extended.FormSelector != '' && Extended.ReValid == false) // Do form validation  loop starts here
        {
                var CurrentFormObj              = $(Extended.FormSelector);
                FormNotificationObj.StoreFormNotification({FormObj:Extended.FormSelector,ShowBorder:Extended.ShowErrBorder,ShowMsg:Extended.ShowErrMsg});
                var GetFormId                   = CurrentFormObj.attr(Extended.FormAttr);
                FormValueInvalid                = false;
                if(GetFormId != '' && GetFormId != undefined)
                {
                        $.each(StoreFormValidation.forms,function(ValidateKey,ValidateVal)
                        {
                                if(ValidateVal.formid == GetFormId)
                                {
                                        FormValueInvalid        = true;
                                        FinalReturnValue        = true;
                                        FieldValidationMethod.CheckFormFieldRules({JsonCheckRule:ValidateVal.formvalidate,FormId:GetFormId});
                                        if(FinalReturnValue == false)
                                        {
                                                CheckFormFieldErrObj.ErrorFieldSetFocus({FormObj:CurrentFormObj,ErrClass:Extended.ErrClass});
                                        }
                                        else
                                        {
                                            ValidationMethods.GenerateHiddenValueFromForm({FormObj:CurrentFormObj});
                                        }
                                        if(Extended.onTypeValid == true)
                                        {
                                                // Form field dynamic valdiation
                                                FieldDynamicValidation.DoValidationDynamic({FormSelector:Extended.FormSelector});
                                        }
                                }
                        });
                        return FinalReturnValue;
                }
        }
        else
        {
                if(Extended.ReValid == true && Extended.FormSelector != '') // Recreated new form validation rules starts here
                {
                        FormNotificationObj.StoreFormNotification({FormObj:Extended.FormSelector,ShowBorder:Extended.ShowErrBorder,ShowMsg:Extended.ShowErrMsg});
                        $(Extended.FormSelector).each(function()
                        {
                                var FormObj     = $(this);
                                var GetFormValdiateId   = FormObj.attr(Extended.FormAttr);
                                var GetFormUniqueId             = GetFormValdiateId;
                                if(GetFormValdiateId != '' && GetFormValdiateId == undefined)
                                {
                                        GetFormUniqueId = ValidationMethods.FormInitialize({GetFormObj:FormObj});
                                }
                                // Form initialize
                                ValidationMethods.CreateValidateRules({FormObj:FormObj,FormIdentify:GetFormUniqueId});
                        });
                        Extended.ReValid = false;
                } // Recreated new form validation rules ends here
                else if(Extended.CreateRules == true) // Get Form field validation rules loop starts here
                {
                        if(Extended.ValidateForm != '' && Extended.FindRule != '')
                        {
                                $(Extended.ValidateForm).each(function()
                                {
                                        var FormObj     = $(this);
                                        // Form initialize
                                        var RandomFormIdentify = ValidationMethods.FormInitialize({GetFormObj:FormObj});
                                        ValidationMethods.CreateValidateRules({FormObj:FormObj,FormIdentify:RandomFormIdentify});
                                });
                        }
                } // Get Form field validation rules loop ends here
        } // Do form validation loop ends here
};//Form validation functions methods ends here
$.FormRule.RemoveFieldRule = function(Options) // Form to remove added form rules starts here
{
        var Defaults    = $.extend({RemoveField:'',},DefaultFormAttr,DynamicFormAttr,Options);
        if(Defaults.FormSelector != '' && Defaults.RemoveField != '')
        {
                var RemoveFormObjAttr   = $(Defaults.FormSelector).attr(Defaults.FormAttr);
                if(RemoveFormObjAttr != '')
                {
                        var JsonRuleValues      = StoreFormValidation.forms;
                        var RuleCheckStatus     = false;
                        $.each(JsonRuleValues,function(a,b)
                        {
                                $.each(b,function()
                                {
                                        if(b.formid == RemoveFormObjAttr && RuleCheckStatus == false)
                                        {
                                                RuleCheckStatus = true;
                                                var RemoveFieldRules    = b.formvalidate;
                                                var SplitRemoveId               = Defaults.RemoveField.split(',');
                                                for (var c = 0; c < SplitRemoveId.length; c++) 
                                                {
                                                    var GetRemoveId = 'Empty';
                                                    $('.'+SplitRemoveId[c]).each(function()
                                                    {
                                                        var RemoveFieldObj      = $(this);
                                                        $.each(RemoveFieldRules,function(d,e)
                                                            {     
                                                                if(e.FieldCode == RemoveFieldObj.attr(Defaults.FieldAttr))
                                                                {
                                                                     GetRemoveId = d;
                                                                }
                                                            });
                                                            if(GetRemoveId != 'Empty')
                                                            {
                                                                RemoveFieldRules.splice(GetRemoveId,1);
                                                            }
                                                    });
                                                } 
                                        }
                                });
                        });
                }
        }
};  // Form to remove added form rules ends here
$.FormRule.ShowFieldErrMsg = function(Options) // Method used to show requested form element error message
{
    var Defaults    = $.extend({ErrField:'',ShowMsg:'',ErrIdentify:''},DefaultFormAttr,DynamicFormAttr,Options);
    if((Defaults.ErrField != '' || Defaults.ErrIdentify != '') && Defaults.ShowMsg != '')
    {
        var RecreateStrObj              = new RecreatedFieldString();
        var CheckFieldErrObj    = new ErrFieldMethods();
        if(Defaults.ErrField != '')
        {
                var CurrentFieldObj     = $('.'+Defaults.ErrField);
                CurrentFieldObj.addClass(Defaults.ErrClass);
                var FieldAttrName               = CurrentFieldObj.attr(Defaults.FieldAttr);
                var FieldIndentifyName  = RecreateStrObj.RecreateErrorIdentify({RecreateString:FieldAttrName});
                CheckFieldErrObj.DisplayErrMsgNode({ErrShowObj:CurrentFieldObj,ErrNodeIdentify:FieldIndentifyName,DisplayErrMsg:Defaults.ShowMsg});
        }
        else
        {
                if(Defaults.ErrIdentify != '')
                {
                        var CurrentFieldObj             = $('input['+Defaults.FieldAttr+'="'+Defaults.ErrIdentify+'"]');
                        var FieldIndentifyName  = RecreateStrObj.RecreateErrorIdentify({RecreateString:Defaults.ErrIdentify});
                        CheckFieldErrObj.DisplayErrMsgNode({ErrShowObj:CurrentFieldObj,ErrNodeIdentify:FieldIndentifyName,DisplayErrMsg:Defaults.ShowMsg});
                }
        }
    }
};
$.FormRule.RemoveFieldErrMsg = function(Options)// Method used to remove requested form element error message
{
    var Defaults    = $.extend({ErrField:'',ErrIdentify:''},DefaultFormAttr,DynamicFormAttr,Options);
    if((Defaults.ErrField != '' || Defaults.ErrIdentify != ''))
    {
            var RecreateStrObj              = new RecreatedFieldString();
            // var CheckFieldErrObj = new ErrFieldMethods();
            if(Defaults.ErrField != '')
            {
                    var CurrentFieldObj     = $('.'+Defaults.ErrField);
                    var FieldAttrName       = CurrentFieldObj.attr(Defaults.FieldAttr);
                    $('input['+Defaults.FieldAttr+'="'+FieldAttrName+'"],select['+Defaults.FieldAttr+'="'+FieldAttrName+'"],textarea['+Defaults.FieldAttr+'="'+FieldAttrName+'"]').removeClass(Defaults.ErrClass);
                    var FieldIndentifyName  = RecreateStrObj.RecreateErrorIdentify({RecreateString:FieldAttrName});
                    $('div.JValidationErr.'+FieldIndentifyName).remove();
            }
            else
            {
                    if(Defaults.ErrIdentify != '')
                    {
                            var FieldIndentifyName  = RecreateStrObj.RecreateErrorIdentify({RecreateString:Defaults.ErrIdentify});
                            $('input['+Defaults.FieldAttr+'="'+Defaults.ErrIdentify+'"],select['+Defaults.FieldAttr+'="'+Defaults.ErrIdentify+'"],textarea['+Defaults.FieldAttr+'="'+Defaults.ErrIdentify+'"]').removeClass(Defaults.ErrClass);
                            $('div.JValidationErr.'+FieldIndentifyName).remove();
                    }
            }
    }
};
$(function(){$.FormRule({CreateRules:true});});