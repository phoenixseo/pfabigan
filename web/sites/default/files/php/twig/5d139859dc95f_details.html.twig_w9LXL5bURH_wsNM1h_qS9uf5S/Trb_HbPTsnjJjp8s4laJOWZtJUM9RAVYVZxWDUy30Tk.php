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

/* themes/contrib/thunder_admin/templates/form/details.html.twig */
class __TwigTemplate_cb07aea4103de911f3573b43bbf8205ca26afbc333086b0fc3908f0476275abc extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = ["if" => 18, "set" => 20, "include" => 29];
        $filters = ["escape" => 17];
        $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['if', 'set', 'include'],
                ['escape'],
                []
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
        // line 17
        echo "<details";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["attributes"] ?? null), "addClass", [0 => "seven-details"], "method")), "html", null, true);
        echo " data-form-description-container>";
        // line 18
        if (($context["title"] ?? null)) {
            // line 20
            $context["summary_classes"] = [0 => "seven-details__summary", 1 => ((            // line 22
($context["required"] ?? null)) ? ("js-form-required") : ("")), 2 => ((            // line 23
($context["required"] ?? null)) ? ("form-required") : (""))];
            // line 26
            echo "    <summary";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["summary_attributes"] ?? null), "addClass", [0 => ($context["summary_classes"] ?? null)], "method")), "html", null, true);
            echo ">
      ";
            // line 27
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title"] ?? null)), "html", null, true);
            // line 28
            if (($context["description"] ?? null)) {
                // line 29
                $this->loadTemplate("@thunder_admin/button-description-toggle.html.twig", "themes/contrib/thunder_admin/templates/form/details.html.twig", 29)->display(twig_array_merge($context, ["class" => "details__toggle-description"]));
            }
            // line 31
            echo "</summary>";
        }
        // line 33
        echo "<div class=\"seven-details__wrapper details-wrapper\">
    ";
        // line 34
        if (($context["errors"] ?? null)) {
            // line 35
            echo "      <div class=\"form-item--error-message\">
        <strong>";
            // line 36
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["errors"] ?? null)), "html", null, true);
            echo "</strong>
      </div>
    ";
        }
        // line 39
        if (($context["description"] ?? null)) {
            // line 40
            echo "<div class=\"seven-details__description js-details-description details-description\">";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["description"] ?? null)), "html", null, true);
            echo "</div>";
        }
        // line 42
        if (($context["children"] ?? null)) {
            // line 43
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["children"] ?? null)), "html", null, true);
        }
        // line 45
        if (($context["value"] ?? null)) {
            // line 46
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["value"] ?? null)), "html", null, true);
        }
        // line 48
        echo "</div>
</details>
";
    }

    public function getTemplateName()
    {
        return "themes/contrib/thunder_admin/templates/form/details.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  111 => 48,  108 => 46,  106 => 45,  103 => 43,  101 => 42,  96 => 40,  94 => 39,  88 => 36,  85 => 35,  83 => 34,  80 => 33,  77 => 31,  74 => 29,  72 => 28,  70 => 27,  65 => 26,  63 => 23,  62 => 22,  61 => 20,  59 => 18,  55 => 17,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "themes/contrib/thunder_admin/templates/form/details.html.twig", "/mnt/webroot/sites/zax-wop/web/themes/contrib/thunder_admin/templates/form/details.html.twig");
    }
}
