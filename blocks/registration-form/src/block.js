const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;
const { SelectControl } = wp.components;
const { useState, useEffect } = wp.element;

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
    
    edit: function(props) {
        const { attributes, setAttributes } = props;
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
            return <div>加载中...</div>;
        }

        const formOptions = forms.map(form => ({
            label: form.title,
            value: form.id
        }));

        return (
            <div className="wp-block-urf-registration-form">
                <SelectControl
                    label="选择表单"
                    value={attributes.formId}
                    options={formOptions}
                    onChange={(formId) => setAttributes({ formId: parseInt(formId) })}
                />
                <div style={{
                    padding: '20px',
                    backgroundColor: '#f8f9fa',
                    border: '1px dashed #dee2e6',
                    textAlign: 'center'
                }}>
                    已选择的表单：{forms.find(f => f.id === attributes.formId)?.title}
                </div>
            </div>
        );
    },
    
    save: function() {
        return null; // 使用 PHP 渲染，所以这里返回 null
    }
}); 