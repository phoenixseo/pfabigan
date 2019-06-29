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

/* themes/contrib/thunder_admin/templates/form/datetime-wrapper.html.twig */
class __TwigTemplate_046c94d9b8120f7da9823443f3ec84ef70881ced8fa7cde69caf0701fb7b24cb extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = ["set" => 17, "if" => 23, "include" => 30];
        $filters = ["escape" => 25];
        $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if', 'include'],
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
        $context["title_classes"] = [0 => "label", 1 => ((        // line 19
($context["required"] ?? null)) ? ("js-form-required") : ("")), 2 => ((        // line 20
($context["required"] ?? null)) ? ("form-required") : (""))];
        // line 23
        echo "<div class=\"form-item datetime-wrapper";
        if (($context["errors"] ?? null)) {
            echo " form-item--error";
        }
        echo "\" data-form-description-container>
  ";
        // line 24
        if (($context["title"] ?? null)) {
            // line 25
            echo "    <label";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["title_attributes"] ?? null), "addClass", [0 => ($context["title_classes"] ?? null)], "method")), "html", null, true);
            echo ">";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title"] ?? null)), "html", null, true);
            echo "</label>
  ";
        }
        // line 27
        echo "  <div class=\"form-item__field-wrapper";
        if (($context["description"] ?? null)) {
            echo " form-item__field-wrapper--has-description";
        }
        echo "\">
    ";
        // line 28
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["content"] ?? null)), "html", null, true);
        echo "
    ";
        // line 29
        if (($context["description"] ?? null)) {
            // line 30
            echo "      ";
            $this->loadTemplate("@thunder_admin/button-description-toggle.html.twig", "themes/contrib/thunder_admin/templates/form/datetime-wrapper.html.twig", 30)->display(twig_array_merge($context, ["class" => "form-item__toggle-description"]));
            // line 31
            echo "    ";
        }
        // line 32
        echo "  </div>
  ";
        // line 33
        if (($context["errors"] ?? null)) {
            // line 34
            echo "    <div class=\"form-item--error-message\">
      <strong>";
            // line 35
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["errors"] ?? null)), "html", null, true);
            echo "</strong>
    </div>
  ";
        }
        // line 38
        echo "  ";
        if (($context["description"] ?? null)) {
            // line 39
            echo "    <div class=\"description js-form-item__description\">";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["description"] ?? null)), "html", null, true);
            echo "</div>
  ";
        }
        // line 41
        echo "</div>

";
    }

    public function getTemplateName()
    {
        return "themes/contrib/thunder_admin/templates/form/datetime-wrapper.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  118 => 41,  112 => 39,  109 => 38,  103 => 35,  100 => 34,  98 => 33,  95 => 32,  92 => 31,  89 => 30,  87 => 29,  83 => 28,  76 => 27,  68 => 25,  66 => 24,  59 => 23,  57 => 20,  56 => 19,  55 => 17,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "themes/contrib/thunder_admin/templates/form/datetime-wrapper.html.twig", "/mnt/webroot/sites/zax-wop/web/themes/contrib/thunder_admin/templates/form/datetime-wrapper.html.twig");
    }
}
