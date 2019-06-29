<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* @thunder_admin/button-description-toggle.html.twig */
class __TwigTemplate_e368df1fcd10e4c23dc53b8d55ffa96f1727c42a79f3a867f803a358fd41841c extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = ["set" => 7];
        $filters = ["escape" => 16];
        $functions = ["create_attribute" => 7];

        try {
            $this->sandbox->checkSecurity(
                ['set'],
                ['escape'],
                ['create_attribute']
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->getSourceContext());

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 7
        $context["button_attributes"] = $this->env->getExtension('Drupal\Core\Template\TwigExtension')->createAttribute();
        // line 9
        $context["button_classes"] = [0 => "button", 1 => "button--small", 2 => "button--icon-only", 3 => ((        // line 13
($context["class"] ?? null)) ? (($context["class"] ?? null)) : (""))];
        // line 16
        echo "<button ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute($this->getAttribute(($context["button_attributes"] ?? null), "addClass", [0 => ($context["button_classes"] ?? null)], "method"), "setAttribute", [0 => "data-toggle-description", 1 => ""], "method"), "setAttribute", [0 => "tabindex", 1 => "-1"], "method")), "html", null, true);
        echo ">
  <span class=\"button__icon\">
    <svg height=\"24\" viewBox=\"0 0 24 24\" width=\"24\" xmlns=\"http://www.w3.org/2000/svg\"><path d=\"M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z\"/></svg>
  </span>
</button>
";
    }

    public function getTemplateName()
    {
        return "@thunder_admin/button-description-toggle.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  60 => 16,  58 => 13,  57 => 9,  55 => 7,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "@thunder_admin/button-description-toggle.html.twig", "/mnt/webroot/sites/zax-wop/web/themes/contrib/thunder_admin/templates/button-description-toggle.html.twig");
    }
}
