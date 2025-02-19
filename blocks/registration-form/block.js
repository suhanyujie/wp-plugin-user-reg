const {
  registerBlockType
} = wp.blocks;
const {
  __
} = wp.i18n;
const {
  SelectControl
} = wp.components;
const {
  useState,
  useEffect
} = wp.element;
registerBlockType('urf/registration-form', {
  title: __('Registration Form'),
  icon: 'feedback',
  category: 'widgets',
  attributes: {
    formId: {
      type: 'number',
      default: 1
    }
  },
  edit: function (props) {
    const {
      attributes,
      setAttributes
    } = props;
    const [forms, setForms] = useState([]);
    const [loading, setLoading] = useState(true);
    useEffect(() => {
      wp.apiFetch({
        path: '/wp/v2/urf/forms'
      }).then(response => {
        setForms(response);
        setLoading(false);
      });
    }, []);
    if (loading) {
      return /*#__PURE__*/React.createElement("div", null, "\u52A0\u8F7D\u4E2D...");
    }
    const formOptions = forms.map(form => ({
      label: form.title,
      value: form.id
    }));
    return /*#__PURE__*/React.createElement("div", {
      className: "wp-block-urf-registration-form"
    }, /*#__PURE__*/React.createElement(SelectControl, {
      label: "\u9009\u62E9\u8868\u5355",
      value: attributes.formId,
      options: formOptions,
      onChange: formId => setAttributes({
        formId: parseInt(formId)
      })
    }), /*#__PURE__*/React.createElement("div", {
      style: {
        padding: '20px',
        backgroundColor: '#f8f9fa',
        border: '1px dashed #dee2e6',
        textAlign: 'center'
      }
    }, "\u5DF2\u9009\u62E9\u7684\u8868\u5355\uFF1A", forms.find(f => f.id === attributes.formId)?.title));
  },
  save: function () {
    return null; // 使用 PHP 渲染，所以这里返回 null
  }
});