using Microsoft.AspNetCore.Mvc;

namespace simkeu.Areas.Users.Controllers
{
  public class UsersController : Controller
  {
    public IActionResult Index()
    {
      return View("~/Areas/Users/Views/Home/Index.cshtml");
    }
  }
}