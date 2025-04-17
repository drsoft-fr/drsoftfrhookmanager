<?php

declare(strict_types=1);

if (!defined('_PS_VERSION_') || !defined('_CAN_LOAD_FILES_')) {
    exit;
}

$autoloadPath = __DIR__ . '/vendor/autoload.php';

if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
}

class drsoftfrhookmanager extends Module
{
    /**
     * @var string $authorEmail Author email
     */
    public $authorEmail;

    /**
     * @var string $moduleGithubRepositoryUrl Module GitHub repository URL
     */
    public $moduleGithubRepositoryUrl;

    /**
     * @var string $moduleGithubIssuesUrl Module GitHub issues URL
     */
    public $moduleGithubIssuesUrl;

    public function __construct()
    {
        $this->name = 'drsoftfrhookmanager';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'drSoft.fr';
        $this->authorEmail = 'contact@drsoft.fr';
        $this->moduleGithubRepositoryUrl = 'https://github.com/drsoft-fr/drsoftfrhookmanager';
        $this->moduleGithubIssuesUrl = 'https://github.com/drsoft-fr/drsoftfrhookmanager/issues';

        parent::__construct();

        $this->bootstrap = true;
        $this->displayName = $this->trans('drSoft.fr Hook Manager', [], 'Modules.Drsoftfrhookmanager.Admin');
        $this->description = $this->trans('Manage the hooks available in your store. Add or remove any hooks as needed.', [], 'Modules.Drsoftfrhookmanager.Admin');
        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall?', [], 'Modules.Drsoftfrhookmanager.Admin');
        $this->table = 'hook';
    }

    /**
     * Retrieves a list of hooks from the database that match specific criteria:
     * - The hook name does not contain 'action'.
     * - The hook name starts with 'display'.
     * - The hook's position is set to 1.
     * The results are ordered by the hook ID in descending order.
     *
     * @return array An array of hooks that meet the specified conditions, or an empty array if no matching hooks are found.
     *
     * @throws PrestaShopDatabaseException
     */
    private function getHooks(): array
    {
        $sql = "SELECT * FROM `" . _DB_PREFIX_ . "hook` WHERE `name` NOT LIKE '%action%' AND `name` LIKE 'display%' AND `position` = 1 ORDER BY id_hook DESC";
        $res = Db::getInstance()->ExecuteS($sql);

        return !empty($res) && is_array($res) ? $res : [];
    }

    /**
     * Generates and returns the HTML code for displaying a list of existing hooks.
     * The list includes columns for hook details such as ID, name, title, description, and activation status.
     * It also allows actions such as deletion to be performed on individual hooks.
     *
     * @return string The generated HTML code for the hook list.
     *
     * @throws PrestaShopDatabaseException
     */
    public function renderForm(): string
    {
        $fields = [
            'id_hook' => [
                'title' => 'ID',
                'width' => 'auto',
                'type' => 'id'
            ],
            'name' => [
                'title' => $this->trans('Name', [], 'Modules.Drsoftfrhookmanager.Admin'),
                'width' => 'auto',
                'type' => 'text'
            ],
            'title' => [
                'title' => $this->trans('Title', [], 'Modules.Drsoftfrhookmanager.Admin'),
                'width' => 'auto',
                'type' => 'text'
            ],
            'description' => [
                'title' => $this->trans('Description', [], 'Modules.Drsoftfrhookmanager.Admin'),
                'width' => 'auto',
                'type' => 'text'
            ],
            'active' => [
                'title' => $this->trans('Active', [], 'Modules.Drsoftfrhookmanager.Admin'),
                'width' => 'auto',
                'type' => 'bool',
                'class' => 'text-center',
            ],
        ];

        $helper = new HelperList();
        $helper->actions = ['delete'];
        $helper->className = 'Hook';
        $helper->simple_header = true;
        $helper->identifier = 'id_hook';
        $helper->show_toolbar = true;
        $helper->title = $this->trans('Available Hooks', [], 'Modules.Drsoftfrhookmanager.Admin');
        $helper->table = $this->table;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&' . http_build_query(['configure' => $this->name]);
        $helper->no_link = true;

        return $helper->generateList($this->getHooks(), $fields) ?: '';
    }

    /**
     * Generates and returns the form configuration for adding a new hook.
     *
     * The form includes fields for specifying attributes such as name, title,
     * description, and activation status of the hook. Additionally, it sets up
     * helper form parameters such as the table name, controller, token, and submit actions.
     *
     * @return string Returns the rendered HTML content of the form as a string.
     */
    public function addForm(): string
    {
        $fields = [
            'form' => [
                'legend' => [
                    'title' => $this->trans('Add new hook', [], 'Modules.Drsoftfrhookmanager.Admin'),
                    'icon' => 'icon-plus-square'
                ],
                'input' => [
                    [
                        'default_value' => '',
                        'type' => 'text',
                        'label' => $this->trans('Name', [], 'Modules.Drsoftfrhookmanager.Admin'),
                        'name' => 'hook_name',
                        'class' => 'fixed-width-lg',
                        'required' => true,
                        'desc' => $this->trans('The hook name is a required field and must be unique. It serves as the primary identifier for the hook.', [], 'Modules.Drsoftfrhookmanager.Admin'),
                        'validation' => 'isGenericName',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->trans('Title', [], 'Modules.Drsoftfrhookmanager.Admin'),
                        'name' => 'hook_title',
                        'class' => 'fixed-width-lg',
                        'required' => false,
                        'desc' => $this->trans('The hook title is displayed in the back office only and is intended for internal reference.', [], 'Modules.Drsoftfrhookmanager.Admin'),
                        'validation' => 'isCleanHtml',
                    ],
                    [
                        'default_value' => '',
                        'type' => 'textarea',
                        'label' => $this->trans('Description', [], 'Modules.Drsoftfrhookmanager.Admin'),
                        'name' => 'hook_description',
                        'class' => 'fixed-width-lg',
                        'required' => false,
                        'desc' => $this->trans('Provide a short description of the hook. This is for internal use and helps clarify its purpose.', [], 'Modules.Drsoftfrhookmanager.Admin'),
                        'validation' => 'isCleanHtml',
                    ],
                    [
                        'default_value' => 1,
                        'type' => 'switch',
                        'label' => $this->trans('Active', [], 'Modules.Drsoftfrhookmanager.Admin'),
                        'name' => 'hook_active',
                        'class' => 'fixed-width-lg',
                        'required' => false,
                        'desc' => $this->trans("Controls the activation of the hook.", [], 'Modules.Drsoftfrhookmanager.Admin'),
                        'values' => [
                            [
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->trans('Enabled', [], 'Modules.Drsoftfrhookmanager.Admin')
                            ],
                            [
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->trans('Disabled', [], 'Modules.Drsoftfrhookmanager.Admin')
                            ]
                        ],
                        'validation' => 'isBool',
                    ],
                ],
                'submit' => [
                    'title' => $this->trans('Add', [], 'Modules.Drsoftfrhookmanager.Admin'),
                ]
            ],
        ];

        $helper = new HelperForm();
        $helper->table = $this->table;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&' . http_build_query(['configure' => $this->name]);
        $helper->submit_action = 'createhook';

        // Default language
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');

        // Load the current value into the form
        $helper->fields_value['hook_name'] = '';
        $helper->fields_value['hook_title'] = '';
        $helper->fields_value['hook_description'] = '';
        $helper->fields_value['hook_active'] = 1;

        return $helper->generateForm([$fields]);
    }

    /**
     * Handles the deletion of a hook by its ID.
     *
     * @param int $hookId The ID of the hook to be deleted.
     *
     * @return void
     */
    private function handleDeleteHook(int $hookId): void
    {
        try {
            $hook = new Hook($hookId);

            if (false === Validate::isLoadedObject($hook)) {
                $this->context->controller->errors[] = $this->trans("This hook could not be found. Please verify the hook ID.", [], 'Modules.Drsoftfrhookmanager.Admin');

                return;
            }

            if (false === $hook->delete()) {
                $this->context->controller->errors[] = $this->trans("This hook cannot be removed by the module.", [], 'Modules.Drsoftfrhookmanager.Admin');

                return;
            }

            $this->context->controller->confirmations[] = $this->trans("Hook successfully removed.", [], 'Modules.Drsoftfrhookmanager.Admin');
        } catch (Throwable $t) {
            $this->context->controller->errors[] = $this->trans(
                "This hook cannot be removed by the module. Error: %s",
                [
                    $t->getMessage()
                ],
                'Modules.Drsoftfrhookmanager.Admin'
            );
        }
    }

    /**
     * Handles the creation of a new hook in the system.
     * Validates the hook name, checks for duplicate hooks, and adds the hook if it passes validation.
     * Provides appropriate success or error messages to the controller context.
     *
     * @return void
     */
    private function handleCreateHook(): void
    {
        try {
            $hookName = Tools::getValue('hook_name', '');

            if (0 !== strpos($hookName, 'display')) {
                $this->context->controller->errors[] = $this->trans("The hook name must start with 'display'.", [], 'Modules.Drsoftfrhookmanager.Admin');

                return;
            }

            if (0 < (int)Hook::getIdByName($hookName)) {
                $this->context->controller->errors[] = $this->trans("This hook already exists in your shop. You cannot add a duplicate.", [], 'Modules.Drsoftfrhookmanager.Admin');

                return;
            }

            if (false === Validate::isGenericName($hookName)) {
                $this->context->controller->errors[] = $this->trans("Invalid hook name.", [], 'Modules.Drsoftfrhookmanager.Admin');

                return;
            }

            $hook = new Hook();
            $hook->name = $hookName;
            $hook->title = strip_tags(Tools::getValue('hook_title', ''));
            $hook->description = strip_tags(Tools::getValue('hook_description', ''));
            $hook->active = (bool)Tools::getValue('hook_visible', 1);
            $hook->position = true;

            if (false === $hook->add()) {
                $this->context->controller->errors[] = $this->trans("The module cannot add this hook.", [], 'Modules.Drsoftfrhookmanager.Admin');

                return;
            }

            $this->context->controller->confirmations[] = $this->trans("Hook created successfully.", [], 'Modules.Drsoftfrhookmanager.Admin');
        } catch (Throwable $t) {
            $this->context->controller->errors[] = $this->trans(
                "The module cannot add this hook.",
                [
                    $t->getMessage()
                ],
                'Modules.Drsoftfrhookmanager.Admin'
            );
        }
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        try {
            if (Tools::isSubmit('deletehook')) {
                $this->handleDeleteHook((int)Tools::getValue('id_hook'));

                return $this->displayForm();
            }

            if (Tools::isSubmit('createhook')) {
                $this->handleCreateHook();
            }

            return $this->displayForm();
        } catch (Throwable $t) {
            return '<div class="alert alert-danger">' . $t->getMessage() . '</div>';
        }
    }

    /**
     * @return string
     *
     * @throws PrestaShopDatabaseException
     */
    public function displayForm(): string
    {
        return $this->addForm() . $this->renderForm();
    }

    /**
     * @return bool
     */
    public function isUsingNewTranslationSystem(): bool
    {
        return true;
    }
}