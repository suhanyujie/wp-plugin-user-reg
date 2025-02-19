<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0"><?php echo esc_html($form->title); ?></h3>
                </div>
                <div class="card-body">
                    <?php if ($form->description): ?>
                        <p class="mb-4"><?php echo esc_html($form->description); ?></p>
                    <?php endif; ?>
                    
                    <form id="urf-registration-form" method="post">
                        <input type="hidden" name="urf_form_id" value="<?php echo esc_attr($form->id); ?>">
                        
                        <?php foreach ($fields as $field): ?>
                            <div class="mb-3">
                                <label for="urf-<?php echo esc_attr($field['name']); ?>" class="form-label">
                                    <?php echo esc_html($field['label']); ?>
                                    <?php if ($field['required']): ?>
                                        <span class="text-danger">*</span>
                                    <?php endif; ?>
                                </label>
                                <input 
                                    type="<?php echo esc_attr($field['type']); ?>"
                                    name="urf_<?php echo esc_attr($field['name']); ?>"
                                    id="urf-<?php echo esc_attr($field['name']); ?>"
                                    class="form-control"
                                    <?php echo $field['required'] ? 'required' : ''; ?>
                                >
                            </div>
                        <?php endforeach; ?>
                        
                        <div class="d-grid">
                            <button type="submit" name="urf_submit" class="btn btn-primary">提交</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 